<?php

namespace App\Services\Stabilization;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Parses sidebar configs and validates routes/schema/controllers (Requirements 13.1-13.9).
 */
class SidebarAnalyzer
{
    protected array $menuItems = [];
    protected array $routeIssues = [];
    protected array $schemaIssues = [];
    protected array $controllerIssues = [];
    protected array $criticalIssues = [];
    protected array $warnings = [];
    protected array $suggestions = [];

    public function analyze(): array
    {
        $this->menuItems = [];
        $this->routeIssues = [];
        $this->schemaIssues = [];
        $this->controllerIssues = [];
        $this->criticalIssues = [];
        $this->warnings = [];
        $this->suggestions = [];

        $this->parseSidebarTemplates();
        $this->validateRoutes();
        $this->validateControllers();

        return [
            'menu_items' => $this->menuItems,
            'route_issues' => $this->routeIssues,
            'schema_issues' => $this->schemaIssues,
            'controller_issues' => $this->controllerIssues,
            'critical' => $this->criticalIssues,
            'warnings' => $this->warnings,
            'suggestions' => $this->suggestions,
        ];
    }

    protected function parseSidebarTemplates(): void
    {
        $templates = config('stabilization.sidebar.blade_templates', [
            'backend/includes/sidebar.blade.php',
        ]);
        $viewsPath = resource_path('views');

        foreach ($templates as $relative) {
            $path = $viewsPath . '/' . str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (!File::exists($path)) {
                continue;
            }
            $content = File::get($path);
            $this->extractMenuItemsFromBlade($path, $content);
        }
    }

    protected function extractMenuItemsFromBlade(string $path, string $content): void
    {
        if (preg_match_all('/href\s*=\s*["\']([^"\']+)["\']|route\s*\(\s*[\'"]([^\'"]+)[\'"]\s*(?:,\s*[^)]*)?\)/', $content, $matches, PREG_SET_ORDER)) {
            $seen = [];
            foreach ($matches as $m) {
                $url = $m[1] ?? $m[2] ?? null;
                if ($url && !isset($seen[$url])) {
                    $seen[$url] = true;
                    $this->menuItems[] = [
                        'source' => $path,
                        'url' => $url,
                        'type' => Str::startsWith($url, 'http') ? 'absolute' : (Str::startsWith($url, '/') ? 'path' : 'route'),
                    ];
                }
            }
        }
        if (preg_match_all('/href\s*=\s*\{\{\s*route\s*\([\'"]([^\'"]+)[\'"]\s*\)\s*\}\}/', $content, $routeMatches)) {
            foreach ($routeMatches[1] as $routeName) {
                $this->menuItems[] = [
                    'source' => $path,
                    'route' => $routeName,
                    'type' => 'route_name',
                ];
            }
        }
    }

    protected function validateRoutes(): void
    {
        $routes = Route::getRoutes();
        $routeNames = [];
        $routeUris = [];
        foreach ($routes as $route) {
            $name = $route->getName();
            if ($name) {
                $routeNames[$name] = $route->uri();
            }
            $routeUris[] = '/' . ltrim($route->uri(), '/');
        }

        foreach ($this->menuItems as $item) {
            if (($item['type'] ?? '') === 'route_name') {
                $name = $item['route'] ?? null;
                if ($name && !isset($routeNames[$name])) {
                    $this->routeIssues[] = [
                        'severity' => 'critical',
                        'message' => "Route name '{$name}' does not exist",
                        'source' => $item['source'] ?? null,
                    ];
                    $this->criticalIssues[] = "Sidebar route missing: {$name}";
                }
                continue;
            }
            $url = $item['url'] ?? null;
            if (!$url || Str::startsWith($url, 'http') || Str::startsWith($url, '#')) {
                continue;
            }
            $path = parse_url($url, PHP_URL_PATH) ?: $url;
            $path = '/' . ltrim($path, '/');
            $matched = false;
            foreach ($routeUris as $uri) {
                if ($path === $uri || Str::is(str_replace('*', '**', $uri), $path)) {
                    $matched = true;
                    break;
                }
            }
            if (!$matched) {
                $this->routeIssues[] = [
                    'severity' => 'warning',
                    'message' => "No route found for path: {$path}",
                    'source' => $item['source'] ?? null,
                ];
                $this->warnings[] = "Sidebar path not found: {$path}";
            }
        }
    }

    protected function validateControllers(): void
    {
        $controllersPath = app_path('Http/Controllers');
        if (!File::isDirectory($controllersPath)) {
            return;
        }
        $files = File::allFiles($controllersPath);
        $resourceMethods = ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'];
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $class = $this->filePathToClass($file->getPathname());
            if (!class_exists($class)) {
                continue;
            }
            $methods = get_class_methods($class);
            $missing = array_diff($resourceMethods, $methods);
            if (count($missing) === count($resourceMethods)) {
                continue;
            }
            if (!empty($missing)) {
                $this->controllerIssues[] = [
                    'controller' => $class,
                    'missing_methods' => array_values($missing),
                    'severity' => count($missing) > 4 ? 'suggestion' : 'warning',
                ];
            }
        }
    }

    protected function filePathToClass(string $path): string
    {
        $base = app_path();
        $rel = Str::after($path, $base . DIRECTORY_SEPARATOR);
        $rel = str_replace(DIRECTORY_SEPARATOR, '\\', $rel);
        $rel = Str::before($rel, '.php');
        return 'App\\' . $rel;
    }

    public function getReportBySeverity(): array
    {
        return [
            'critical' => $this->criticalIssues,
            'warnings' => $this->warnings,
            'suggestions' => $this->suggestions,
        ];
    }
}
