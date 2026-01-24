<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AISensy WhatsApp Integration Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AISensy WhatsApp API integration.
    | Used for sending mock test notifications via WhatsApp.
    |
    */

    'api_url' => env('AISENSY_API_URL', 'https://api.aisensy.com/v1/messages'),
    'api_key' => env('AISENSY_API_KEY'),
    'campaign_id' => env('AISENSY_CAMPAIGN_ID'),
    'enabled' => env('AISENSY_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Templates
    |--------------------------------------------------------------------------
    |
    | Template names configured in your AISensy account.
    | These should match the template names in your AISensy dashboard.
    |
    */

    'templates' => [
        'mock_test_assigned' => 'mock_test_assigned',
        'mock_test_scheduled' => 'mock_test_scheduled',
        'mock_test_rescheduled' => 'mock_test_rescheduled',
        'mock_test_result' => 'mock_test_result',
    ],
];
