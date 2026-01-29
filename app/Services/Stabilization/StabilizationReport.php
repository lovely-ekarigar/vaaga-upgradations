<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\File;

/**
 * Tracks and formats stabilization issues (Requirements 12.1, 12.2, 12.3, 13.9).
 */
class StabilizationReport
{
    protected array $critical = [];
    protected array $warnings = [];
    protected array $suggestions = [];
    protected array $context = [];
    protected float $startTime;
    protected array $stats = [];

    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    public function addCritical(string $message, array $context = []): void
    {
        $this->critical[] = ['message' => $message, 'context' => $context];
    }

    public function addWarning(string $message, array $context = []): void
    {
        $this->warnings[] = ['message' => $message, 'context' => $context];
    }

    public function addSuggestion(string $message, array $context = []): void
    {
        $this->suggestions[] = ['message' => $message, 'context' => $context];
    }

    public function setStat(string $key, $value): void
    {
        $this->stats[$key] = $value;
    }

    public function getCritical(): array
    {
        return $this->critical;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function getStats(): array
    {
        return array_merge($this->stats, [
            'duration_seconds' => round(microtime(true) - $this->startTime, 2),
            'critical_count' => count($this->critical),
            'warning_count' => count($this->warnings),
            'suggestion_count' => count($this->suggestions),
        ]);
    }

    /**
     * Human-readable text report (Requirement 14.2).
     */
    public function toText(): string
    {
        $lines = [
            '========================================',
            'Laravel Application Stabilization Report',
            '========================================',
            '',
            'Summary',
            '--------',
            'Critical:   ' . count($this->critical),
            'Warnings:   ' . count($this->warnings),
            'Suggestions: ' . count($this->suggestions),
            'Duration:   ' . ($this->getStats()['duration_seconds'] ?? 0) . 's',
            '',
        ];

        if (!empty($this->critical)) {
            $lines[] = 'CRITICAL ISSUES';
            $lines[] = '---------------';
            foreach ($this->critical as $i => $item) {
                $lines[] = ($i + 1) . '. ' . ($item['message'] ?? $item);
                if (!empty($item['context'])) {
                    $lines[] = '   Context: ' . json_encode($item['context']);
                }
            }
            $lines[] = '';
        }

        if (!empty($this->warnings)) {
            $lines[] = 'WARNINGS';
            $lines[] = '---------';
            foreach ($this->warnings as $i => $item) {
                $lines[] = ($i + 1) . '. ' . ($item['message'] ?? $item);
            }
            $lines[] = '';
        }

        if (!empty($this->suggestions)) {
            $lines[] = 'SUGGESTIONS';
            $lines[] = '-----------';
            foreach (array_slice($this->suggestions, 0, 20) as $i => $item) {
                $lines[] = ($i + 1) . '. ' . ($item['message'] ?? $item);
            }
            if (count($this->suggestions) > 20) {
                $lines[] = '... and ' . (count($this->suggestions) - 20) . ' more.';
            }
        }

        return implode("\n", $lines);
    }

    /**
     * JSON export for programmatic access (Requirement 14.2).
     */
    public function toArray(): array
    {
        return [
            'summary' => $this->getStats(),
            'critical' => $this->critical,
            'warnings' => $this->warnings,
            'suggestions' => $this->suggestions,
        ];
    }

    public function saveToFile(): string
    {
        $dir = config('stabilization.report.output_dir', storage_path('app/stabilization'));
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $jsonPath = $dir . '/' . (config('stabilization.report.json_file') ?? 'stabilization-report.json');
        $txtPath = $dir . '/' . (config('stabilization.report.text_file') ?? 'stabilization-report.txt');
        File::put($jsonPath, json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        File::put($txtPath, $this->toText());
        return $jsonPath;
    }
}
