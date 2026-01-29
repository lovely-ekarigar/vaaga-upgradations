<?php

namespace App\Console\Commands;

use App\Services\Stabilization\SchemaAnalyzer;
use Illuminate\Console\Command;

class AnalyzeSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stabilize:analyze-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze database schema for missing columns, invalid foreign keys, and schema mismatches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting schema analysis...');
        $this->newLine();

        $analyzer = new SchemaAnalyzer();

        // Analyze missing columns
        $this->info('1. Analyzing missing columns...');
        $missingColumns = $analyzer->findMissingColumns();
        
        if (empty($missingColumns)) {
            $this->info('   ✓ No missing columns found');
        } else {
            $this->warn('   ✗ Found missing columns in ' . count($missingColumns) . ' table(s)');
            foreach ($missingColumns as $table => $columns) {
                // Flatten nested arrays
                $flatColumns = is_array($columns) ? array_values((array)$columns) : [$columns];
                $columnList = [];
                foreach ($flatColumns as $col) {
                    if (is_array($col)) {
                        $columnList = array_merge($columnList, $col);
                    } else {
                        $columnList[] = $col;
                    }
                }
                $this->line("     - Table '{$table}': " . implode(', ', $columnList));
            }
        }
        $this->newLine();

        // Analyze invalid foreign keys
        $this->info('2. Analyzing foreign key constraints...');
        $invalidConstraints = $analyzer->findInvalidForeignKeys();
        
        if (empty($invalidConstraints)) {
            $this->info('   ✓ No invalid foreign key constraints found');
        } else {
            $this->warn('   ✗ Found ' . count($invalidConstraints) . ' invalid foreign key constraint(s)');
            foreach ($invalidConstraints as $constraint) {
                $this->line("     - Table '{$constraint['table']}', Constraint '{$constraint['constraint_name']}':");
                foreach ($constraint['issues'] as $issue) {
                    $this->line("       • {$issue}");
                }
            }
        }
        $this->newLine();

        // Analyze schema mismatches
        $this->info('3. Analyzing schema mismatches...');
        $mismatches = $analyzer->findSchemaMismatches();
        
        if (empty($mismatches)) {
            $this->info('   ✓ No schema mismatches found');
        } else {
            $this->warn('   ✗ Found ' . count($mismatches) . ' schema mismatch(es)');
            foreach ($mismatches as $mismatch) {
                $this->line("     - {$mismatch['message']}");
                if (isset($mismatch['columns'])) {
                    $this->line("       Columns: " . implode(', ', $mismatch['columns']));
                }
            }
        }
        $this->newLine();

        // Summary
        $totalIssues = count($missingColumns) + count($invalidConstraints) + count($mismatches);
        
        if ($totalIssues === 0) {
            $this->info('✓ Schema analysis complete: No issues found!');
        } else {
            $this->warn("✗ Schema analysis complete: {$totalIssues} issue(s) found");
            $this->newLine();
            $this->info('Run "php artisan stabilize:generate-fixes" to generate migration files to fix these issues.');
        }

        return 0;
    }
}
