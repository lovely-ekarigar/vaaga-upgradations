<?php

namespace App\Services\Stabilization;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;

/**
 * Standardizes API responses for consistency (Requirements 6.1, 6.2, 6.3).
 */
class ResponseStandardizer
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('stabilization.response', [
            'success_key' => 'success',
            'data_key' => 'data',
            'message_key' => 'message',
            'errors_key' => 'errors',
            'meta_key' => 'meta',
            'pagination_keys' => ['current_page', 'last_page', 'per_page', 'total', 'from', 'to'],
        ]);
    }

    /**
     * Success response for a single resource (Requirement 6.1).
     */
    public function success($data, string $message = null, int $status = 200): JsonResponse
    {
        $payload = [
            $this->config['success_key'] => true,
            $this->config['data_key'] => $data,
        ];
        if ($message !== null) {
            $payload[$this->config['message_key']] = $message;
        }
        return response()->json($payload, $status);
    }

    /**
     * Collection response for paginated data (Requirement 6.2, 6.4).
     */
    public function collection($data, string $message = null, int $status = 200): JsonResponse
    {
        $dataPayload = $data instanceof AbstractPaginator || $data instanceof AbstractCursorPaginator
            ? $data->items()
            : (is_array($data) ? $data : ($data instanceof Collection ? $data->all() : $data));
        $payload = [
            $this->config['success_key'] => true,
            $this->config['data_key'] => $dataPayload,
        ];

        if ($data instanceof AbstractPaginator) {
            $payload[$this->config['meta_key']] = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ];
        } elseif ($data instanceof AbstractCursorPaginator) {
            $payload[$this->config['meta_key']] = [
                'path' => $data->path(),
                'per_page' => $data->perPage(),
                'next_cursor' => $data->nextCursor()?->encode(),
                'prev_cursor' => $data->previousCursor()?->encode(),
            ];
        }

        if ($message !== null) {
            $payload[$this->config['message_key']] = $message;
        }
        return response()->json($payload, $status);
    }

    /**
     * Error response (Requirement 6.3).
     */
    public function error(string $message, $errors = null, int $status = 400): JsonResponse
    {
        $payload = [
            $this->config['success_key'] => false,
            $this->config['message_key'] => $message,
        ];
        if ($errors !== null) {
            $payload[$this->config['errors_key']] = is_array($errors) ? $errors : ['errors' => $errors];
        }
        return response()->json($payload, $status);
    }

    /**
     * Validate that an array response follows the standard format (for analyzers).
     */
    public function validateStructure(array $response): array
    {
        $issues = [];
        if (!array_key_exists($this->config['success_key'], $response)) {
            $issues[] = 'Missing "' . $this->config['success_key'] . '" key';
        }
        if (($response[$this->config['success_key']] ?? false) === true) {
            if (!array_key_exists($this->config['data_key'], $response)) {
                $issues[] = 'Success response missing "' . $this->config['data_key'] . '" key';
            }
        } else {
            if (!array_key_exists($this->config['message_key'], $response)) {
                $issues[] = 'Error response should include "' . $this->config['message_key'] . '"';
            }
        }
        return $issues;
    }

    /**
     * Validate pagination metadata completeness (Requirement 6.4).
     */
    public function validatePaginationMeta(array $meta): array
    {
        $required = $this->config['pagination_keys'] ?? ['current_page', 'last_page', 'per_page', 'total'];
        $issues = [];
        foreach ($required as $key) {
            if (!array_key_exists($key, $meta)) {
                $issues[] = "Pagination meta missing: {$key}";
            }
        }
        return $issues;
    }
}
