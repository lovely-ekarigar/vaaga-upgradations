<?php
/**
 * Script to search for the old codebase directory
 */

$searchPath = 'C:\Users\malik\Downloads';
$patterns = ['*vaaga*', '*mock*', '*public_html*'];

$found = [];

// Check if the directory exists
if (!is_dir($searchPath)) {
    echo "NOT FOUND - Search path does not exist: $searchPath\n";
    exit(1);
}

// Search for directories matching patterns
foreach ($patterns as $pattern) {
    $fullPattern = $searchPath . DIRECTORY_SEPARATOR . $pattern;
    $matches = glob($fullPattern, GLOB_ONLYDIR);
    foreach ($matches as $match) {
        $found[] = $match;
    }
}

// Remove duplicates
$found = array_unique($found);

if (empty($found)) {
    echo "NOT FOUND - No directories matching 'vaaga', 'mock', or 'public_html' found in $searchPath\n";
    exit(0);
}

echo "FOUND directories:\n";
foreach ($found as $dir) {
    echo $dir . "\n";
}
