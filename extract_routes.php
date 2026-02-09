<?php
$content = file_get_contents('route_references.txt');

// Convert from UTF-16LE to UTF-8
$content = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');

// Remove BOM if present
$content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

// Find all route names - match route('name') or route("name")
preg_match_all("/route\(\s*['\"]([^'\"]+)['\"]/", $content, $matches);

$route_names = array_unique($matches[1]);
sort($route_names);

// Write to file
file_put_contents('view_route_names.txt', implode("\n", $route_names));

echo "Found " . count($route_names) . " unique routes in views" . PHP_EOL;
echo "First 50:" . PHP_EOL;
echo implode("\n", array_slice($route_names, 0, 50));
?>
