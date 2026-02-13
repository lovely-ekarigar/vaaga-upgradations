<?php
/**
 * Laravel App Directory Comparison Script
 * Compares app directories between current project and old project
 */

$currentProject = 'c:\Projects\vaagaacademy';
$oldProject = 'C:\Users\malik\Downloads\_public_html_vaaga_mock';

echo "===========================================\n";
echo "LARAVEL APP DIRECTORY COMPARISON\n";
echo "===========================================\n\n";

echo "Current Project: {$currentProject}\n";
echo "Old Project: {$oldProject}\n\n";

// Function to get all PHP files recursively
function getPhpFiles($directory) {
    $files = [];
    if (!is_dir($directory)) {
        return $files;
    }
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $relativePath = str_replace($directory . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $files[$relativePath] = $file->getPathname();
        }
    }
    ksort($files);
    return $files;
}

// Function to get files in a specific directory (non-recursive)
function getFilesInDir($directory) {
    $files = [];
    if (!is_dir($directory)) {
        return $files;
    }
    $iterator = new DirectoryIterator($directory);
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[$file->getFilename()] = $file->getPathname();
        }
    }
    ksort($files);
    return $files;
}

// Function to find missing files
function findMissingFiles($oldFiles, $currentFiles) {
    $missing = [];
    foreach ($oldFiles as $relativePath => $fullPath) {
        if (!isset($currentFiles[$relativePath])) {
            $missing[$relativePath] = $fullPath;
        }
    }
    return $missing;
}

// Function to display file list
function displayFileList($files, $title) {
    echo "\n--- {$title} ---\n";
    if (empty($files)) {
        echo "No files found.\n";
        return;
    }
    foreach ($files as $relativePath => $fullPath) {
        echo "  ✓ {$relativePath}\n";
    }
    echo "Total: " . count($files) . " files\n";
}

// Function to display missing files
function displayMissingFiles($files, $title) {
    echo "\n===========================================\n";
    echo "{$title}\n";
    echo "===========================================\n";
    if (empty($files)) {
        echo "✓ No missing files.\n";
        return;
    }
    echo "⚠ MISSING FILES (exist in old but not in current):\n\n";
    foreach ($files as $relativePath => $fullPath) {
        echo "  ✗ {$relativePath}\n";
    }
    echo "\nTotal Missing: " . count($files) . " files\n";
}

// ============================
// 1. CONTROLLERS COMPARISON
// ============================
echo "\n" . str_repeat("=", 60);
echo "\n1. CONTROLLERS COMPARISON\n";
echo str_repeat("=", 60);

$currentControllersDir = $currentProject . '\app\Http\Controllers';
$oldControllersDir = $oldProject . '\app\Http\Controllers';

$currentControllers = getPhpFiles($currentControllersDir);
$oldControllers = getPhpFiles($oldControllersDir);

displayFileList($currentControllers, "Current Project Controllers");
displayFileList($oldControllers, "Old Project Controllers");

$missingControllers = findMissingFiles($oldControllers, $currentControllers);
displayMissingFiles($missingControllers, "MISSING CONTROLLERS");

// ============================
// 2. MODELS COMPARISON
// ============================
echo "\n" . str_repeat("=", 60);
echo "\n2. MODELS COMPARISON\n";
echo str_repeat("=", 60);

$currentModelsDir = $currentProject . '\app\Models';
$oldModelsDir = $oldProject . '\app\Models';

$currentModels = getPhpFiles($currentModelsDir);
$oldModels = getPhpFiles($oldModelsDir);

displayFileList($currentModels, "Current Project Models");
displayFileList($oldModels, "Old Project Models");

$missingModels = findMissingFiles($oldModels, $currentModels);
displayMissingFiles($missingModels, "MISSING MODELS");

// ============================
// 3. KERNEL.PHP COMPARISON
// ============================
echo "\n" . str_repeat("=", 60);
echo "\n3. KERNEL.PHP COMPARISON\n";
echo str_repeat("=", 60);

$currentKernel = $currentProject . '\app\Http\Kernel.php';
$oldKernel = $oldProject . '\app\Http\Kernel.php';

if (file_exists($currentKernel) && file_exists($oldKernel)) {
    $currentKernelContent = file_get_contents($currentKernel);
    $oldKernelContent = file_exists($oldKernel) ? file_get_contents($oldKernel) : '';
    
    echo "\n--- Current Kernel.php ---\n";
    echo "File size: " . strlen($currentKernelContent) . " bytes\n";
    
    echo "\n--- Old Kernel.php ---\n";
    echo "File size: " . strlen($oldKernelContent) . " bytes\n";
    
    if ($currentKernelContent === $oldKernelContent) {
        echo "\n✓ Kernel.php files are IDENTICAL\n";
    } else {
        echo "\n⚠ Kernel.php files are DIFFERENT\n";
        
        // Extract middleware arrays from both files
        preg_match('/protected \$middleware = \[(.*?)\];/s', $currentKernelContent, $currentMiddleware);
        preg_match('/protected \$middleware = \[(.*?)\];/s', $oldKernelContent, $oldMiddleware);
        
        preg_match('/protected \$middlewareGroups = \[(.*?)\];/s', $currentKernelContent, $currentGroups);
        preg_match('/protected \$middlewareGroups = \[(.*?)\];/s', $oldKernelContent, $oldGroups);
        
        preg_match('/protected \$routeMiddleware = \[(.*?)\];/s', $currentKernelContent, $currentRoute);
        preg_match('/protected \$routeMiddleware = \[(.*?)\];/s', $oldKernelContent, $oldRoute);
        
        echo "\n--- Middleware Differences ---\n";
        if (!empty($oldMiddleware[0]) && (empty($currentMiddleware[0]) || $oldMiddleware[0] !== $currentMiddleware[0])) {
            echo "⚠ \$middleware array differs\n";
        }
        if (!empty($oldGroups[0]) && (empty($currentGroups[0]) || $oldGroups[0] !== $currentGroups[0])) {
            echo "⚠ \$middlewareGroups array differs\n";
        }
        if (!empty($oldRoute[0]) && (empty($currentRoute[0]) || $oldRoute[0] !== $currentRoute[0])) {
            echo "⚠ \$routeMiddleware array differs\n";
        }
    }
} else {
    echo "\n✗ One or both Kernel.php files not found\n";
}

// ============================
// 4. PROVIDERS COMPARISON
// ============================
echo "\n" . str_repeat("=", 60);
echo "\n4. PROVIDERS COMPARISON\n";
echo str_repeat("=", 60);

$currentProvidersDir = $currentProject . '\app\Providers';
$oldProvidersDir = $oldProject . '\app\Providers';

$currentProviders = getFilesInDir($currentProvidersDir);
$oldProviders = getFilesInDir($oldProvidersDir);

displayFileList($currentProviders, "Current Project Providers");
displayFileList($oldProviders, "Old Project Providers");

$missingProviders = findMissingFiles($oldProviders, $currentProviders);
displayMissingFiles($missingProviders, "MISSING PROVIDERS");

// ============================
// 5. MIDDLEWARE COMPARISON
// ============================
echo "\n" . str_repeat("=", 60);
echo "\n5. MIDDLEWARE COMPARISON\n";
echo str_repeat("=", 60);

$currentMiddlewareDir = $currentProject . '\app\Http\Middleware';
$oldMiddlewareDir = $oldProject . '\app\Http\Middleware';

$currentMiddleware = getFilesInDir($currentMiddlewareDir);
$oldMiddleware = getFilesInDir($oldMiddlewareDir);

displayFileList($currentMiddleware, "Current Project Middleware");
displayFileList($oldMiddleware, "Old Project Middleware");

$missingMiddleware = findMissingFiles($oldMiddleware, $currentMiddleware);
displayMissingFiles($missingMiddleware, "MISSING MIDDLEWARE");

// ============================
// 6. FULL APP DIRECTORY COMPARISON
// ============================
echo "\n" . str_repeat("=", 60);
echo "\n6. FULL APP DIRECTORY COMPARISON\n";
echo str_repeat("=", 60);

$currentAppDir = $currentProject . '\app';
$oldAppDir = $oldProject . '\app';

$currentAllFiles = getPhpFiles($currentAppDir);
$oldAllFiles = getPhpFiles($oldAppDir);

displayFileList($currentAllFiles, "All Current Project App Files");
displayFileList($oldAllFiles, "All Old Project App Files");

$missingAllFiles = findMissingFiles($oldAllFiles, $currentAllFiles);
displayMissingFiles($missingAllFiles, "ALL MISSING FILES IN APP DIRECTORY");

// ============================
// 7. SUMMARY
// ============================
echo "\n" . str_repeat("=", 60);
echo "\n7. SUMMARY\n";
echo str_repeat("=", 60);

echo "\n📊 STATISTICS:\n";
echo "  Controllers - Current: " . count($currentControllers) . ", Old: " . count($oldControllers) . ", Missing: " . count($missingControllers) . "\n";
echo "  Models - Current: " . count($currentModels) . ", Old: " . count($oldModels) . ", Missing: " . count($missingModels) . "\n";
echo "  Providers - Current: " . count($currentProviders) . ", Old: " . count($oldProviders) . ", Missing: " . count($missingProviders) . "\n";
echo "  Middleware - Current: " . count($currentMiddleware) . ", Old: " . count($oldMiddleware) . ", Missing: " . count($missingMiddleware) . "\n";
echo "  Total App Files - Current: " . count($currentAllFiles) . ", Old: " . count($oldAllFiles) . ", Missing: " . count($missingAllFiles) . "\n";

echo "\n\n✅ COMPARISON COMPLETE!\n";

// Save results to file
$outputFile = $currentProject . '\app_comparison_results.txt';
$handle = fopen($outputFile, 'w');
fwrite($handle, "LARAVEL APP DIRECTORY COMPARISON RESULTS\n");
fwrite($handle, "Generated: " . date('Y-m-d H:i:s') . "\n\n");

fwrite($handle, "MISSING CONTROLLERS (" . count($missingControllers) . "):\n");
foreach ($missingControllers as $file => $path) {
    fwrite($handle, "  - {$file}\n");
}

fwrite($handle, "\nMISSING MODELS (" . count($missingModels) . "):\n");
foreach ($missingModels as $file => $path) {
    fwrite($handle, "  - {$file}\n");
}

fwrite($handle, "\nMISSING PROVIDERS (" . count($missingProviders) . "):\n");
foreach ($missingProviders as $file => $path) {
    fwrite($handle, "  - {$file}\n");
}

fwrite($handle, "\nMISSING MIDDLEWARE (" . count($missingMiddleware) . "):\n");
foreach ($missingMiddleware as $file => $path) {
    fwrite($handle, "  - {$file}\n");
}

fwrite($handle, "\n\nALL MISSING FILES (" . count($missingAllFiles) . "):\n");
foreach ($missingAllFiles as $file => $path) {
    fwrite($handle, "  - {$file}\n");
}

fclose($handle);
echo "\n📝 Results saved to: {$outputFile}\n";
