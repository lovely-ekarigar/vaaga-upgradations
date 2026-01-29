<?php

use App\Services\Stabilization\ResponseStandardizer;

/**
 * Property 8: API Response Structure Consistency (Requirements 4.3, 6.1, 6.2, 6.3)
 */
test('response standardizer success structure has success and data keys', function () {
    $standardizer = new ResponseStandardizer();
    $response = $standardizer->success(['id' => 1]);
    $data = json_decode($response->getContent(), true);
    expect($data)->toHaveKey('success')->and($data['success'])->toBeTrue();
    expect($data)->toHaveKey('data')->and($data['data'])->toBeArray();
});

test('response standardizer collection structure has success data and optional meta', function () {
    $standardizer = new ResponseStandardizer();
    $collection = new \Illuminate\Pagination\LengthAwarePaginator([['id' => 1]], 1, 15);
    $response = $standardizer->collection($collection);
    $data = json_decode($response->getContent(), true);
    expect($data)->toHaveKey('success')->and($data['success'])->toBeTrue();
    expect($data)->toHaveKey('data')->and($data['data'])->toBeArray();
    expect($data)->toHaveKey('meta');
    expect($data['meta'])->toHaveKeys(['current_page', 'last_page', 'per_page', 'total', 'from', 'to']);
});

test('response standardizer error structure has success false and message', function () {
    $standardizer = new ResponseStandardizer();
    $response = $standardizer->error('Validation failed', ['field' => ['Required']]);
    $data = json_decode($response->getContent(), true);
    expect($data)->toHaveKey('success')->and($data['success'])->toBeFalse();
    expect($data)->toHaveKey('message')->and($data['message'])->toBe('Validation failed');
    expect($data)->toHaveKey('errors');
});

/**
 * Property 9: Pagination Metadata Completeness (Requirement 6.4)
 */
test('validate pagination meta requires expected keys', function () {
    $standardizer = new ResponseStandardizer();
    $meta = ['current_page' => 1, 'last_page' => 5, 'per_page' => 10, 'total' => 50, 'from' => 1, 'to' => 10];
    $issues = $standardizer->validatePaginationMeta($meta);
    expect($issues)->toBeArray()->and($issues)->toBeEmpty();
});

test('validate pagination meta reports missing keys', function () {
    $standardizer = new ResponseStandardizer();
    $issues = $standardizer->validatePaginationMeta(['current_page' => 1]);
    expect($issues)->toBeArray()->and($issues)->not->toBeEmpty();
});
