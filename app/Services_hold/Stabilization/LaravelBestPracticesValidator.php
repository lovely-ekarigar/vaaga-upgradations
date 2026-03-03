<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\File;

/**
 * Validates Laravel best practices (Requirements 11.1, 11.3, 11.4, 11.5, 11.6).
 */
class LaravelBestPracticesValidator
{
    protected array $rawSqlUsages = [];
    protected array $folderStructureIssues = [];
    protected array $resourcePatternIssues = [];
    protected array $routeBindingSuggestions = [];
    protected array $authUsageIssues = [];

    public function analyze(): array
    {
        $this->rawSqlUsages = [];
        $this->folderStructureIssues = [];
        $this->resourcePatternIssues = [];
        $this->routeBindingSuggestions = [];
        $this->authUsageIssues = [];

        $this->checkEloquentVsRawSql();
        $this->checkFolderStructure();
        $this->checkResourceControllers();
        $this->checkRouteModelBinding();
        $this->checkAuthUsage();

        return [
            'raw_sql' => $this->rawSqlUsages,
            'folder_structure' => $this->folderStructureIssues,
            'resource_pattern' => $this->resourcePatternIssues,
            'route_binding' => $this->routeBindingSuggestions,
            'auth_usage' => $this->authUsageIssues,
        ];
    }

    protected function checkEloquentVsRawSql(): void
    {
        $path = app_path();
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $content = File::get($file->getPathname());
            if (preg_match('/DB::(select|insert|update|delete|statement)\s*\(/i', $content) && !preg_match('/\/\/\s*allow raw|@allow-raw/', $content)) {
                $this->rawSqlUsages[] = [
                    'file' => $file->getPathname(),
                    'message' => 'Raw SQL usage; consider Eloquent or Query Builder',
                ];
            }
        }
    }

    protected function checkFolderStructure(): void
    {
        $expected = [
            'Http/Controllers' => app_path('Http/Controllers'),
            'Models' => app_path('Models'),
            'Http/Middleware' => app_path('Http/Middleware'),
        ];
        foreach ($expected as $name => $path) {
            if (!File::isDirectory($path)) {
                $this->folderStructureIssues[] = [
                    'message' => "Expected directory missing: {$name}",
                    'path' => $path,
                ];
            }
        }
    }

    protected function checkResourceControllers(): void
    {
        $controllersPath = app_path('Http/Controllers');
        if (!File::isDirectory($controllersPath)) {
            return;
        }
        $resourceMethods = ['index', 'show', 'store', 'update', 'destroy'];
        $files = File::allFiles($controllersPath);
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $content = File::get($file->getPathname());
            $hasResource = false;
            foreach ($resourceMethods as $m) {
                if (preg_match('/function\s+' . $m . '\s*\(/i', $content)) {
                    $hasResource = true;
                    break;
                }
            }
            if ($hasResource && preg_match('/function\s+(\w+)\s*\([^)]*\$id\s*\)/i', $content) && !preg_match('/\w+\s+\$(\w+)\s*\)/', $content)) {
                $this->resourcePatternIssues[] = [
                    'file' => $file->getPathname(),
                    'message' => 'Controller may benefit from resource route and route model binding',
                ];
            }
        }
    }

    protected function checkRouteModelBinding(): void
    {
        $controllersPath = app_path('Http/Controllers');
        if (!File::isDirectory($controllersPath)) {
            return;
        }
        $files = File::allFiles($controllersPath);
        foreach ($files as $file) {
            $content = File::get($file->getPathname());
            if (preg_match('/\$\w+\s*=\s*\w+::find\s*\(\s*\$?(request->|)\w+\s*\)/', $content)) {
                $this->routeBindingSuggestions[] = [
                    'file' => $file->getPathname(),
                    'message' => 'Manual ID lookup; consider route model binding',
                ];
            }
        }
    }

    protected function checkAuthUsage(): void
    {
        $path = app_path('Http/Controllers');
        if (!File::isDirectory($path)) {
            return;
        }
        $files = File::allFiles($path);
        foreach ($files as $file) {
            $content = File::get($file->getPathname());
            if (preg_match('/auth\(\)->user\(\)|Auth::user\(\)|auth\(\)->id\(\)/', $content)) {
                continue;
            }
            if (preg_match('/\$request->user\(\)|request\(\)->user\(\)/', $content)) {
                continue;
            }
            if (preg_match('/session\(\)->get\s*\(\s*[\'"](?:user_id|auth)[\'"]\s*\)/', $content)) {
                $this->authUsageIssues[] = [
                    'file' => $file->getPathname(),
                    'message' => 'Session-based user access; consider Laravel Auth facade',
                ];
            }
        }
    }
}
