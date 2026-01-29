<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Standard API Response Keys
    |--------------------------------------------------------------------------
    */
    'response' => [
        'success_key' => 'success',
        'data_key' => 'data',
        'message_key' => 'message',
        'errors_key' => 'errors',
        'meta_key' => 'meta',
        'pagination_keys' => ['current_page', 'last_page', 'per_page', 'total', 'from', 'to'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Analyzer Paths
    |--------------------------------------------------------------------------
    */
    'paths' => [
        'controllers' => app_path('Http/Controllers'),
        'models' => app_path('Models'),
        'migrations' => database_path('migrations'),
        'views' => resource_path('views'),
        'frontend_js' => resource_path('js'),
        'routes' => base_path('routes'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sidebar / Menu Sources
    |--------------------------------------------------------------------------
    */
    'sidebar' => [
        'blade_templates' => [
            'backend/includes/sidebar.blade.php',
            'frontend/include/user-menu.blade.php',
        ],
        'config_files' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Output
    |--------------------------------------------------------------------------
    */
    'report' => [
        'output_dir' => storage_path('app/stabilization'),
        'json_file' => 'stabilization-report.json',
        'text_file' => 'stabilization-report.txt',
    ],

];
