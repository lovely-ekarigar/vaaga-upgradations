<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Parses Vue/React/Blade, extracts API calls and property access (Requirements 7.1, 8.1, 8.2).
 */
class FrontendIntegrationAnalyzer
{
    protected array $apiCalls = [];
    protected array $propertyAccess = [];
    protected array $mismatches = [];
    protected array $nullSafetyIssues = [];

    public function analyze(): array
    {
        $this->apiCalls = [];
        $this->propertyAccess = [];
        $this->mismatches = [];
        $this->nullSafetyIssues = [];

        $paths = [
            resource_path('views'),
            resource_path('js'),
        ];

        foreach ($paths as $basePath) {
            if (!File::isDirectory($basePath)) {
                continue;
            }
            $files = File::allFiles($basePath);
            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['php', 'blade.php', 'vue', 'js', 'jsx', 'ts', 'tsx'])) {
                    $this->analyzeFile($file->getPathname(), $file->getContents(), $ext);
                }
            }
        }

        return [
            'api_calls' => $this->apiCalls,
            'property_access' => $this->propertyAccess,
            'data_structure_mismatches' => $this->mismatches,
            'null_safety_issues' => $this->nullSafetyIssues,
        ];
    }

    protected function analyzeFile(string $path, string $content, string $ext): void
    {
        $this->extractApiCalls($path, $content);
        $this->extractPropertyAccess($path, $content);
        $this->extractNullSafetyIssues($path, $content);
    }

    protected function extractApiCalls(string $path, string $content): void
    {
        $patterns = [
            '/axios\.(get|post|put|patch|delete)\s*\(\s*[\'"`]([^\'"`]+)[\'"`]/',
            '/fetch\s*\(\s*[\'"`]([^\'"`]+)[\'"`]/',
            '/\$\.(get|post|ajax)\s*\(\s*[\'"`]([^\'"`]+)[\'"`]/',
            '/route\s*\(\s*[\'"`]([^\'"`]+)[\'"`]/',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $url = $m[2] ?? $m[1] ?? null;
                    $method = $m[1] ?? 'GET';
                    if ($url) {
                        $this->apiCalls[] = [
                            'file' => $path,
                            'method' => strtoupper($method),
                            'url' => $url,
                        ];
                    }
                }
            }
        }
    }

    protected function extractPropertyAccess(string $path, string $content): void
    {
        if (preg_match_all('/\.(data|response\.data)\.(\w+)(\.\w+)*/i', $content, $matches)) {
            foreach ($matches[0] as $access) {
                $this->propertyAccess[] = [
                    'file' => $path,
                    'access' => trim($access),
                ];
            }
        }
        if (preg_match_all('/\{\{\s*\$?(\w+)(\.\w+)*\s*\}\}/', $content, $bladeMatches)) {
            foreach ($bladeMatches[0] as $access) {
                $this->propertyAccess[] = [
                    'file' => $path,
                    'access' => 'blade: ' . trim($access),
                ];
            }
        }
    }

    protected function extractNullSafetyIssues(string $path, string $content): void
    {
        $lines = explode("\n", $content);
        foreach ($lines as $num => $line) {
            if (preg_match('/\.(data|response)\.\w+\.\w+/', $line) && !preg_match('/\?\.[\w\.]+|\.optional\(|&&|\.has\(|\?\s*:/', $line)) {
                $this->nullSafetyIssues[] = [
                    'file' => $path,
                    'line' => $num + 1,
                    'snippet' => Str::limit(trim($line), 80),
                ];
            }
        }
    }

    /**
     * Compare frontend expectations with actual API response (Requirements 7.1-7.4).
     */
    public function detectDataStructureMismatches(array $apiResponses): array
    {
        $this->mismatches = [];
        foreach ($this->propertyAccess as $access) {
            $expected = $access['access'];
            foreach ($apiResponses as $endpoint => $structure) {
                if (!$this->pathExistsInStructure($expected, $structure)) {
                    $this->mismatches[] = [
                        'file' => $access['file'],
                        'expected_path' => $expected,
                        'endpoint' => $endpoint,
                        'actual_keys' => $this->getTopLevelKeys($structure),
                    ];
                }
            }
        }
        return $this->mismatches;
    }

    protected function pathExistsInStructure(string $path, $structure): bool
    {
        $parts = preg_split('/\./', trim($path), -1, PREG_SPLIT_NO_EMPTY);
        $current = is_array($structure) ? $structure : (array) $structure;
        foreach ($parts as $part) {
            $part = preg_replace('/^data\.|^response\./', '', $part);
            if ($part === 'data' || $part === 'response') {
                continue;
            }
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return false;
            }
            $current = $current[$part];
        }
        return true;
    }

    protected function getTopLevelKeys($structure): array
    {
        if (is_array($structure)) {
            return array_keys($structure);
        }
        if (is_object($structure)) {
            return array_keys(get_object_vars($structure));
        }
        return [];
    }
}
