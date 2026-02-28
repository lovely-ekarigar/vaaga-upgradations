<?php
/**
 * This script finds and fixes PHP files that have methods outside the class
 * due to misplaced closing braces.
 */

$directory = 'app/Http/Controllers';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$fixed = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Pattern to find: closing brace followed by whitespace and then a docblock/method
        $pattern = '/\}\s*\n\n+(\s*\/\*\*\s*\n\s*\*\s*\w+.*\n\s*\*\/\s*\n\s*public\s+function)/s';
        
        if (preg_match($pattern, $content, $matches)) {
            echo "Found issue in: " . $file->getPathname() . "\n";
            
            // Replace the pattern - remove the extra closing brace and whitespace
            $fixed_content = preg_replace($pattern, "\n\n\$1", $content, 1);
            
            // Check if there's a duplicate closing brace at the end
            // Pattern: method ending with } followed by } at end of file
            $end_pattern = '/(public\s+function\s+\w+\s*\([^)]*\)\s*\{[^}]+\})\s*\n\}/s';
            if (preg_match($end_pattern, $fixed_content)) {
                $fixed_content = preg_replace($end_pattern, "\$1", $fixed_content, 1);
            }
            
            file_put_contents($file->getPathname(), $fixed_content);
            $fixed[] = $file->getPathname();
            echo "Fixed: " . $file->getPathname() . "\n\n";
        }
    }
}

echo "Total files fixed: " . count($fixed) . "\n";
foreach ($fixed as $f) {
    echo "  - $f\n";
}
?>
