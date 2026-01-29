<?php

namespace App\Console\Commands;

use App\Services\Stabilization\FixOrchestrator;
use App\Services\Stabilization\StabilizationReport;
use Illuminate\Console\Command;

class StabilizeFixCommand extends Command
{
    protected $signature = 'stabilize:fix
                            {--dry-run : Preview fixes without applying (default) }
                            {--apply : Apply generated fixes }';

    protected $description = 'Generate migrations and fixes in order: database → backend → frontend (Requirements 1.4, 1.5)';

    public function handle(): int
    {
        $dryRun = !$this->option('apply');
        if ($dryRun) {
            $this->warn('Running in dry-run mode. Use --apply to write fixes.');
        } else {
            if (!$this->confirm('Apply fixes to the codebase?', false)) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }
        }

        $report = new StabilizationReport();
        $orchestrator = new FixOrchestrator($dryRun);
        $result = $orchestrator->run($report);

        $this->line($report->toText());
        $report->saveToFile();

        if (!empty($result['fixes']['migrations'] ?? [])) {
            $this->info('Generated migrations: ' . implode(', ', $result['fixes']['migrations']));
        }
        if (!empty($result['applied'])) {
            $this->table(['Fix type', 'Count'], collect($result['applied'])->map(fn ($c, $k) => [$k, $c])->values()->all());
        }

        return (count($report->getCritical()) > 0) ? self::FAILURE : self::SUCCESS;
    }
}
