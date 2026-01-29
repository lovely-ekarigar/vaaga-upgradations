<?php

namespace App\Console\Commands;

use App\Services\Stabilization\SidebarAnalyzer;
use Illuminate\Console\Command;

class StabilizeSidebarCommand extends Command
{
    protected $signature = 'stabilize:sidebar
                            {--output= : Write report to file }';

    protected $description = 'Validate all sidebar modules: routes, schema, controllers (Requirements 13.1, 13.2, 13.4, 13.5)';

    public function handle(): int
    {
        $this->info('Validating sidebar modules...');
        $analyzer = new SidebarAnalyzer();
        $result = $analyzer->analyze();

        $this->table(
            ['Category', 'Count'],
            [
                ['Menu items found', count($result['menu_items'] ?? [])],
                ['Route issues', count($result['route_issues'] ?? [])],
                ['Controller issues', count($result['controller_issues'] ?? [])],
                ['Critical', count($result['critical'] ?? [])],
                ['Warnings', count($result['warnings'] ?? [])],
            ]
        );

        if (!empty($result['route_issues'])) {
            $this->newLine();
            $this->error('Route issues:');
            $this->table(
                ['Severity', 'Message', 'Source'],
                collect($result['route_issues'])->map(fn ($r) => [
                    $r['severity'] ?? '',
                    $r['message'] ?? '',
                    $r['source'] ?? '',
                ])->take(20)->toArray()
            );
        }

        if (!empty($result['critical'])) {
            $this->newLine();
            $this->error('Critical issues:');
            foreach (array_slice($result['critical'], 0, 10) as $msg) {
                $this->line('  - ' . $msg);
            }
        }

        $path = $this->option('output');
        if ($path) {
            \Illuminate\Support\Facades\File::put($path, json_encode($result, JSON_PRETTY_PRINT));
            $this->info('Report written to: ' . $path);
        }

        return (count($result['critical'] ?? []) > 0) ? self::FAILURE : self::SUCCESS;
    }
}
