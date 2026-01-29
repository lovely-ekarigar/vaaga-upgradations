<?php

use App\Services\Stabilization\FrontendIntegrationAnalyzer;

/**
 * Property 14: Frontend-Backend Data Structure Alignment (Requirements 7.1-7.4)
 * Property 15: Frontend API Call Correctness (Requirements 8.1-8.3)
 * Property 18: Response Parsing Safety (Requirements 9.3-9.5)
 */
test('frontend analyzer returns api_calls array', function () {
    $analyzer = new FrontendIntegrationAnalyzer();
    $result = $analyzer->analyze();
    expect($result)->toHaveKey('api_calls')->and($result['api_calls'])->toBeArray();
});

test('frontend analyzer returns property_access array', function () {
    $analyzer = new FrontendIntegrationAnalyzer();
    $result = $analyzer->analyze();
    expect($result)->toHaveKey('property_access')->and($result['property_access'])->toBeArray();
});

test('frontend analyzer returns null_safety_issues array', function () {
    $analyzer = new FrontendIntegrationAnalyzer();
    $result = $analyzer->analyze();
    expect($result)->toHaveKey('null_safety_issues')->and($result['null_safety_issues'])->toBeArray();
});

test('frontend analyzer returns data_structure_mismatches array', function () {
    $analyzer = new FrontendIntegrationAnalyzer();
    $result = $analyzer->analyze();
    expect($result)->toHaveKey('data_structure_mismatches')->and($result['data_structure_mismatches'])->toBeArray();
});
