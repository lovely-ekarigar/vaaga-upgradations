<?php
$routes_json = file_get_contents('routes_full.json');
$routes = json_decode($routes_json, true);

if (!$routes) {
    echo "Failed to parse routes JSON" . PHP_EOL;
    exit(1);
}

$admin_routes = [];
foreach ($routes as $route) {
    if (isset($route['name']) && strpos($route['name'], 'admin.') === 0) {
        $admin_routes[] = $route['name'];
    }
}

$admin_routes = array_unique($admin_routes);
sort($admin_routes);

file_put_contents('defined_admin_routes.txt', implode("\n", $admin_routes));
echo "Found " . count($admin_routes) . " defined admin routes" . PHP_EOL;
echo "First 20: " . implode(", ", array_slice($admin_routes, 0, 20)) . PHP_EOL;
?>
