<?php
/**
 * Compare routes and config directories between two Laravel projects
 */

$currentProject = 'c:\Projects\vaagaacademy';
$oldProject = 'C:\Users\malik\Downloads\_public_html_vaaga_mock';

echo "=======================================================\n";
echo "LARAVEL PROJECT COMPARISON\n";
echo "=======================================================\n";
echo "Current: {$currentProject}\n";
echo "Old:     {$oldProject}\n";
echo "=======================================================\n\n";

// Helper function to get all files recursively
function getAllFiles($dir, $extension = null) {
    if (!is_dir($dir)) {
        return [];
    }
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $filepath = $file->getPathname();
            if ($extension === null || $file->getExtension() === $extension) {
                $files[] = str_replace($dir . DIRECTORY_SEPARATOR, '', $filepath);
            }
        }
    }
    return $files;
}

// Helper function to get files in a directory (non-recursive)
function getFilesInDir($dir, $extension = null) {
    if (!is_dir($dir)) {
        return [];
    }
    $files = [];
    foreach (glob($dir . '/*') as $file) {
        if (is_file($file)) {
            if ($extension === null || pathinfo($file, PATHINFO_EXTENSION) === $extension) {
                $files[] = basename($file);
            }
        }
    }
    return $files;
}

// ============================================
// 1. LIST ROUTE FILES
// ============================================
echo "1. ROUTE FILES COMPARISON\n";
echo "-------------------------------------------------------\n";

$currentRoutesDir = $currentProject . '\routes';
$oldRoutesDir = $oldProject . '\routes';

$currentRouteFiles = getFilesInDir($currentRoutesDir, 'php');
$oldRouteFiles = getFilesInDir($oldRoutesDir, 'php');

echo "Current Project Route Files:\n";
sort($currentRouteFiles);
foreach ($currentRouteFiles as $file) {
    echo "  - {$file}\n";
}

echo "\nOld Project Route Files:\n";
sort($oldRouteFiles);
foreach ($oldRouteFiles as $file) {
    echo "  - {$file}\n";
}

// Missing in current
$missingRouteFiles = array_diff($oldRouteFiles, $currentRouteFiles);
echo "\n>>> MISSING ROUTE FILES in Current Project:\n";
if (empty($missingRouteFiles)) {
    echo "  (None - all route files exist)\n";
} else {
    foreach ($missingRouteFiles as $file) {
        echo "  - {$file}\n";
    }
}

// Extra in current
$extraRouteFiles = array_diff($currentRouteFiles, $oldRouteFiles);
echo "\n>>> EXTRA ROUTE FILES in Current Project:\n";
if (empty($extraRouteFiles)) {
    echo "  (None)\n";
} else {
    foreach ($extraRouteFiles as $file) {
        echo "  - {$file}\n";
    }
}

echo "\n\n";

// ============================================
// 2. LIST CONFIG FILES
// ============================================
echo "2. CONFIG FILES COMPARISON\n";
echo "-------------------------------------------------------\n";

$currentConfigDir = $currentProject . '\config';
$oldConfigDir = $oldProject . '\config';

$currentConfigFiles = getFilesInDir($currentConfigDir, 'php');
$oldConfigFiles = getFilesInDir($oldConfigDir, 'php');

echo "Current Project Config Files:\n";
sort($currentConfigFiles);
foreach ($currentConfigFiles as $file) {
    echo "  - {$file}\n";
}

echo "\nOld Project Config Files:\n";
sort($oldConfigFiles);
foreach ($oldConfigFiles as $file) {
    echo "  - {$file}\n";
}

// Missing in current
$missingConfigFiles = array_diff($oldConfigFiles, $currentConfigFiles);
echo "\n>>> MISSING CONFIG FILES in Current Project:\n";
if (empty($missingConfigFiles)) {
    echo "  (None - all config files exist)\n";
} else {
    foreach ($missingConfigFiles as $file) {
        echo "  - {$file}\n";
    }
}

// Extra in current
$extraConfigFiles = array_diff($currentConfigFiles, $oldConfigFiles);
echo "\n>>> EXTRA CONFIG FILES in Current Project:\n";
if (empty($extraConfigFiles)) {
    echo "  (None)\n";
} else {
    foreach ($extraConfigFiles as $file) {
        echo "  - {$file}\n";
    }
}

echo "\n\n";

// ============================================
// 3. COMPARE WEB.PHP ROUTES
// ============================================
echo "3. WEB.PHP ROUTES COMPARISON\n";
echo "-------------------------------------------------------\n";

$currentWebPhp = $currentRoutesDir . '\web.php';
$oldWebPhp = $oldRoutesDir . '\web.php';

function extractRoutes($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    $routes = [];
    
    // Match Route::get/post/put/delete/patch/any/match/resource/group
    $patterns = [
        // Route::get('/path', ...)
        '/Route::(get|post|put|delete|patch|any|match)\s*\(\s*[\'"]([^\'"]+)[\'"]/i',
        // Route::resource('name', ...)
        '/Route::(resource|apiResource)\s*\(\s*[\'"]([^\'"]+)[\'"]/i',
    ];
    
    foreach ($patterns as $pattern) {
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $method = strtolower($match[1]);
            $path = $match[2];
            $routes[] = "{$method}:{$path}";
        }
    }
    
    // Also extract named routes ->name('name')
    preg_match_all('/->name\([\'"]([^\'"]+)[\'"]\)/', $content, $nameMatches);
    $namedRoutes = $nameMatches[1];
    
    return ['paths' => $routes, 'names' => $namedRoutes];
}

function extractRoutePatterns($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    $patterns = [];
    
    foreach ($lines as $line) {
        $line = trim($line);
        // Look for Route definitions
        if (preg_match('/Route::(get|post|put|delete|patch|any|match|resource|apiResource)/i', $line)) {
            // Clean up the line for display
            $cleanLine = preg_replace('/\s+/', ' ', $line);
            if (strlen($cleanLine) > 150) {
                $cleanLine = substr($cleanLine, 0, 150) . '...';
            }
            $patterns[] = $cleanLine;
        }
    }
    return $patterns;
}

$currentRoutes = extractRoutes($currentWebPhp);
$oldRoutes = extractRoutes($oldWebPhp);

$currentRoutePatterns = extractRoutePatterns($currentWebPhp);
$oldRoutePatterns = extractRoutePatterns($oldWebPhp);

echo "Current web.php has " . count($currentRoutePatterns) . " route definitions\n";
echo "Old web.php has " . count($oldRoutePatterns) . " route definitions\n\n";

// Find missing route paths
$missingPaths = array_diff($oldRoutes['paths'], $currentRoutes['paths']);
echo ">>> MISSING ROUTE PATHS in Current web.php:\n";
if (empty($missingPaths)) {
    echo "  (None - all route paths exist)\n";
} else {
    foreach ($missingPaths as $path) {
        echo "  - {$path}\n";
    }
}

// Find missing named routes
$missingNames = array_diff($oldRoutes['names'], $currentRoutes['names']);
echo "\n>>> MISSING NAMED ROUTES in Current web.php:\n";
if (empty($missingNames)) {
    echo "  (None - all named routes exist)\n";
} else {
    foreach ($missingNames as $name) {
        echo "  - {$name}\n";
    }
}

echo "\n\n";

// ============================================
// 4. DETAILED ROUTE COMPARISON
// ============================================
echo "4. DETAILED ROUTE PATTERN COMPARISON\n";
echo "-------------------------------------------------------\n";

// Convert patterns to a comparable format
function normalizeRoute($line) {
    // Remove whitespace variations
    $line = preg_replace('/\s+/', ' ', trim($line));
    // Remove comments
    $line = preg_replace('/\/\/.*/', '', $line);
    return trim($line);
}

$currentNormalized = array_map('normalizeRoute', $currentRoutePatterns);
$oldNormalized = array_map('normalizeRoute', $oldRoutePatterns);

$missingPatterns = [];
foreach ($oldNormalized as $i => $oldPattern) {
    $found = false;
    foreach ($currentNormalized as $currentPattern) {
        // Check if similar (allowing for some variation)
        similar_text($oldPattern, $currentPattern, $percent);
        if ($percent > 85) {
            $found = true;
            break;
        }
    }
    if (!$found) {
        $missingPatterns[] = $oldRoutePatterns[$i];
    }
}

echo ">>> ROUTE PATTERNS IN OLD BUT NOT IN CURRENT:\n";
if (empty($missingPatterns)) {
    echo "  (None found - or differences are minor)\n";
} else {
    $count = 0;
    foreach ($missingPatterns as $pattern) {
        echo "  - {$pattern}\n";
        $count++;
        if ($count >= 30) {
            echo "  ... and " . (count($missingPatterns) - 30) . " more\n";
            break;
        }
    }
}

echo "\n\n";

// ============================================
// 5. SUMMARY
// ============================================
echo "5. SUMMARY\n";
echo "-------------------------------------------------------\n";
echo "Route Files Missing:     " . count($missingRouteFiles) . "\n";
echo "Config Files Missing:    " . count($missingConfigFiles) . "\n";
echo "Route Paths Missing:     " . count($missingPaths) . "\n";
echo "Named Routes Missing:    " . count($missingNames) . "\n";
echo "Route Patterns Missing:  " . count($missingPatterns) . "\n";

echo "\n\n>>> QUICK REFERENCE - MISSING FILES:\n";

if (!empty($missingRouteFiles)) {
    echo "\nRoute files to copy:\n";
    foreach ($missingRouteFiles as $file) {
        echo "  copy \"{$oldProject}\\routes\\{$file}\" \"{$currentProject}\\routes\\{$file}\"\n";
    }
}

if (!empty($missingConfigFiles)) {
    echo "\nConfig files to copy:\n";
    foreach ($missingConfigFiles as $file) {
        echo "  copy \"{$oldProject}\\config\\{$file}\" \"{$currentProject}\\config\\{$file}\"\n";
    }
}

echo "\n=======================================================\n";
echo "COMPARISON COMPLETE\n";
echo "=======================================================\n";
