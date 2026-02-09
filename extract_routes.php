<?php
$content = file_get_contents('route_references.txt');

// Find all route names - match route('name') or route("name")
preg_match_all("/route\(\s*['\"]([^'\"]+)['\"]/i", $content, $matches);
$route_names = array_unique($matches[1]);
sort($route_names);

// Write to file
file_put_contents('view_route_names.txt', implode("\n", $route_names));

echo implode("\n", array_slice($route_names, 0, 200));
echo "\n\n... total " . count($route_names) . " routes\n";
?>
