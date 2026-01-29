<?php

use App\Services\Stabilization\SidebarAnalyzer;

/**
 * Property 25: Sidebar Module Discovery Completeness (Requirement 13.1)
 */
test('sidebar analyzer returns menu items array', function () {
    $analyzer = new SidebarAnalyzer();
    $result = $analyzer->analyze();
    expect($result)->toHaveKey('menu_items')->and($result['menu_items'])->toBeArray();
});

/**
 * Property 26: Sidebar Route Validation (Requirement 13.2)
 */
test('sidebar analyzer returns route issues array', function () {
    $analyzer = new SidebarAnalyzer();
    $result = $analyzer->analyze();
    expect($result)->toHaveKey('route_issues')->and($result['route_issues'])->toBeArray();
});

test('sidebar analyzer returns critical warnings and suggestions', function () {
    $analyzer = new SidebarAnalyzer();
    $result = $analyzer->analyze();
    expect($result)->toHaveKeys(['critical', 'warnings', 'suggestions']);
    expect($result['critical'])->toBeArray();
    expect($result['warnings'])->toBeArray();
    expect($result['suggestions'])->toBeArray();
});
