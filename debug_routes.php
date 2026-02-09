<?php
$content = file_get_contents('route_references.txt');
echo 'File size: ' . strlen($content) . ' bytes' . PHP_EOL;
echo 'First 200 chars: ' . substr($content, 0, 200) . PHP_EOL;
echo PHP_EOL;

// Check if route( is in the file
if (strpos($content, 'route(') !== false) {
    echo "Found 'route(' in file" . PHP_EOL;
}

// Try simple match
if (preg_match('/route\(/', $content, $m)) {
    echo "Regex matched 'route('" . PHP_EOL;
}

// Try to match route('admin.dashboard')
if (preg_match("/route\('admin\.dashboard'\)/", $content, $m)) {
    echo "Matched route('admin.dashboard')" . PHP_EOL;
}

// Extract all route names
preg_match_all("/route\(\s*['\"]([^'\"]+)['\"]/", $content, $matches);
echo "Found " . count($matches[1]) . " route names" . PHP_EOL;
echo "First 10: " . implode(", ", array_slice($matches[1], 0, 10)) . PHP_EOL;

// Let's check what single quote looks like
$pos = strpos($content, "route('");
echo "Position of route(': $pos" . PHP_EOL;
if ($pos !== false) {
    echo "Chars around it: " . substr($content, $pos, 30) . PHP_EOL;
}
?>
