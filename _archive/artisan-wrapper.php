#!/usr/bin/env php
<?php
/**
 * Wrapper for artisan that suppresses PHP 8.1 deprecation warnings
 * Usage: php artisan-wrapper.php [artisan commands]
 */

// Suppress deprecation warnings before loading anything
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT);

// Set custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if ($errno === E_DEPRECATED || $errno === E_STRICT) {
        return true; // Suppress
    }
    return false; // Let through
}, E_ALL);

// Include the actual artisan file
require __DIR__ . '/artisan';
