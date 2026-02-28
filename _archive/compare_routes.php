<?php
// Load view routes
$view_routes = file('view_route_names.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$view_routes = array_unique(array_map('trim', $view_routes));

// Load defined admin routes
$defined_routes = file('defined_routes.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$defined_routes = array_unique(array_map('trim', $defined_routes));

echo "View routes: " . count($view_routes) . PHP_EOL;
echo "Defined admin routes: " . count($defined_routes) . PHP_EOL;

// Find routes in views that are not defined
$missing = array_diff($view_routes, $defined_routes);

echo PHP_EOL . "=== ROUTES USED IN VIEWS BUT NOT DEFINED ===" . PHP_EOL;
echo "Missing routes: " . count($missing) . PHP_EOL;
foreach ($missing as $route) {
    echo "  - $route" . PHP_EOL;
}

// Save missing routes to file
file_put_contents('missing_routes.txt', implode("\n", $missing));
echo PHP_EOL . "Missing routes saved to missing_routes.txt" . PHP_EOL;

// Also check if any view file uses a problematic pattern
$patterns = [
    'admin.admin.' => 'Double admin prefix',
    'admin.' => 'Regular admin routes',
];

echo PHP_EOL . "=== ANALYSIS ===" . PHP_EOL;
foreach ($patterns as $pattern => $desc) {
    $count = 0;
    foreach ($view_routes as $route) {
        if (strpos($route, $pattern) !== false) {
            $count++;
        }
    }
    echo "$desc: $count routes" . PHP_EOL;
}
?>
