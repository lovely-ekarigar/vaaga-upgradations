<?php

namespace App\Console\Commands;

use App\Services\Stabilization\FixOrchestrator;
use App\Services\Stabilization\FrontendIntegrationAnalyzer;
use App\Services\Stabilization\LaravelBestPracticesValidator;
use App\Services\Stabilization\ResponseStandardizer;
use App\Services\Stabilization\SchemaAnalyzer;
use App\Services\Stabilization\SidebarAnalyzer;
use App\Services\Stabilization\StabilizationReport;
use Illuminate\Console\Command;

class StabilizeAnalyzeCommand extends Command
{
    protected $signature = 'stabilize:analyze
                            {--analyzer=* : Run only these analyzers (schema,controller,sidebar,frontend,best-practices,response)}
                            {--output= : Write report to this path }
                            {--json : Output JSON only }';

    protected $description = 'Run all stabilization analyzers and output report (Requirements 1.1, 1.2, 1.3)';

    public function handle(): int
    {
        $this->info('Running stabilization analysis...');
        $report = new StabilizationReport();
        $analyzers = $this->option('analyzer') ?: ['schema', 'controller', 'sidebar', 'frontend', 'best-practices', 'response'];

        if (in_array('schema', $analyzers)) {
            $this->info('  [1/6] Schema analyzer...');
            $schema = new SchemaAnalyzer();
            foreach ($schema->findMissingColumns() as $table => $cols) {
                $flat = is_array($cols) ? array_map(fn ($c) => is_string($c) ? $c : json_encode($c), $cols) : [$cols];
                $report->addCritical("Table {$table} missing columns: " . implode(', ', $flat), ['table' => $table, 'columns' => $cols]);
            }
            foreach ($schema->findInvalidForeignKeys() as $fk) {
                $report->addWarning('Invalid foreign key: ' . ($fk['CONSTRAINT_NAME'] ?? 'unknown'), $fk);
            }
        }

        if (in_array('controller', $analyzers)) {
            $this->info('  [2/6] Controller analyzer...');
            $controller = new \App\Services\Stabilization\ControllerAnalyzer();
            foreach ($controller->analyze() as $issue) {
                $report->addWarning($issue['message'] ?? 'Controller issue', $issue);
            }
        }

        if (in_array('sidebar', $analyzers)) {
            $this->info('  [3/6] Sidebar analyzer...');
            $sidebar = new SidebarAnalyzer();
            $result = $sidebar->analyze();
            foreach ($result['critical'] ?? [] as $msg) {
                $report->addCritical($msg);
            }
            foreach ($result['warnings'] ?? [] as $msg) {
                $report->addWarning($msg);
            }
        }

        if (in_array('frontend', $analyzers)) {
            $this->info('  [4/6] Frontend integration analyzer...');
            $frontend = new FrontendIntegrationAnalyzer();
            $result = $frontend->analyze();
            foreach ($result['null_safety_issues'] ?? [] as $issue) {
                $report->addSuggestion('Null safety: ' . ($issue['snippet'] ?? $issue['file'] ?? ''), $issue);
            }
        }

        if (in_array('best-practices', $analyzers)) {
            $this->info('  [5/6] Laravel best practices...');
            $validator = new LaravelBestPracticesValidator();
            $result = $validator->analyze();
            foreach ($result['raw_sql'] ?? [] as $item) {
                $report->addSuggestion($item['message'] ?? 'Raw SQL usage', $item);
            }
            foreach ($result['route_binding'] ?? [] as $item) {
                $report->addSuggestion($item['message'] ?? 'Route binding', $item);
            }
        }

        if (in_array('response', $analyzers)) {
            $this->info('  [6/6] Response format...');
            $standardizer = new ResponseStandardizer();
            $report->setStat('response_config', $standardizer->validateStructure(['success' => true, 'data' => []]));
        }

        $path = $this->option('output');
        if (!$path) {
            $path = $report->saveToFile();
        } else {
            \Illuminate\Support\Facades\File::put($path, $this->option('json') ? json_encode($report->toArray(), JSON_PRETTY_PRINT) : $report->toText());
        }

        if ($this->option('json')) {
            $this->line(json_encode($report->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $this->line($report->toText());
            $this->info('Report saved to: ' . $path);
        }

        $stats = $report->getStats();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Critical', $stats['critical_count'] ?? 0],
                ['Warnings', $stats['warning_count'] ?? 0],
                ['Suggestions', $stats['suggestion_count'] ?? 0],
                ['Duration (s)', $stats['duration_seconds'] ?? 0],
            ]
        );

        return ($stats['critical_count'] ?? 0) > 0 ? self::FAILURE : self::SUCCESS;
    }
}
