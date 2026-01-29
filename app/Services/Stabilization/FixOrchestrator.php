<?php

namespace App\Services\Stabilization;

/**
 * Coordinates analyzers and fix generation in order: database → backend → frontend (Requirements 1.4, 1.5).
 */
class FixOrchestrator
{
    protected array $appliedFixes = [];
    protected bool $dryRun = true;

    public function __construct(bool $dryRun = true)
    {
        $this->dryRun = $dryRun;
    }

    public function setDryRun(bool $dryRun): self
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    /**
     * Run all analyzers and collect issues; then generate fixes in order.
     */
    public function run(StabilizationReport $report): array
    {
        $report->setStat('phase', 'analysis');

        $schemaAnalyzer = new SchemaAnalyzer();
        $missingColumns = $schemaAnalyzer->findMissingColumns();
        $invalidFks = $schemaAnalyzer->findInvalidForeignKeys();
        foreach ($missingColumns as $table => $columns) {
            $report->addCritical("Table {$table} missing columns: " . implode(', ', $columns), ['table' => $table, 'columns' => $columns]);
        }
        foreach ($invalidFks as $fk) {
            $report->addWarning("Invalid foreign key: " . ($fk['CONSTRAINT_NAME'] ?? 'unknown'), $fk);
        }

        $controllerAnalyzer = new ControllerAnalyzer();
        $controllerIssues = $controllerAnalyzer->analyze();
        foreach ($controllerIssues as $issue) {
            $report->addWarning($issue['message'] ?? 'Controller issue', $issue);
        }

        $sidebarAnalyzer = new SidebarAnalyzer();
        $sidebarResult = $sidebarAnalyzer->analyze();
        foreach ($sidebarResult['critical'] ?? [] as $msg) {
            $report->addCritical($msg);
        }
        foreach ($sidebarResult['warnings'] ?? [] as $msg) {
            $report->addWarning($msg);
        }

        $frontendAnalyzer = new FrontendIntegrationAnalyzer();
        $frontendResult = $frontendAnalyzer->analyze();
        foreach ($frontendResult['null_safety_issues'] ?? [] as $issue) {
            $report->addSuggestion('Null safety: ' . ($issue['snippet'] ?? $issue['file'] ?? ''), $issue);
        }

        $report->setStat('phase', 'fix_generation');
        $fixes = [];

        if (!$this->dryRun && !empty($missingColumns)) {
            $migrationGenerator = new MigrationGenerator();
            $suggested = $migrationGenerator->generateForMissingColumns($missingColumns);
            $fixes['migrations'] = $suggested;
            $this->appliedFixes['migrations'] = count($suggested);
        }

        return [
            'report' => $report->toArray(),
            'fixes' => $fixes,
            'applied' => $this->appliedFixes,
        ];
    }

    public function getAppliedFixes(): array
    {
        return $this->appliedFixes;
    }
}
