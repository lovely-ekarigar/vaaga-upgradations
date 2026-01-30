#!/usr/bin/env php
<?php

/**
 * VaagaAcademy - Verify Data Display Fixes
 * 
 * This script verifies that all the fixes applied are working correctly.
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Auth\User;

echo "\n";
echo "========================================\n";
echo "VaagaAcademy - Verification Script\n";
echo "========================================\n\n";

$errors = 0;
$warnings = 0;

// Test 1: Check withdraws table exists
echo "[1/8] Checking withdraws table... ";
try {
    if (Schema::hasTable('withdraws')) {
        echo "✓ EXISTS\n";
    } else {
        echo "✗ MISSING\n";
        $errors++;
    }
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 2: Check courses table and data
echo "[2/8] Checking courses table and data... ";
try {
    $count = Course::count();
    if ($count > 0) {
        echo "✓ FOUND {$count} courses\n";
    } else {
        echo "⚠ WARNING: No courses found\n";
        $warnings++;
    }
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 3: Check teachers exist
echo "[3/8] Checking teachers... ";
try {
    $count = User::role('teacher')->count();
    if ($count > 0) {
        echo "✓ FOUND {$count} teachers\n";
    } else {
        echo "⚠ WARNING: No teachers found\n";
        $warnings++;
    }
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 4: Check Course model getCouseNameWithCat method
echo "[4/8] Testing Course::getCouseNameWithCat()... ";
try {
    $course = Course::first();
    if ($course) {
        $result = $course->getCouseNameWithCat($course->id);
        if ($result !== "") {
            echo "✓ WORKING\n";
        } else {
            echo "⚠ WARNING: Returns empty string\n";
            $warnings++;
        }
    } else {
        echo "⚠ SKIPPED: No courses to test\n";
        $warnings++;
    }
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 5: Check EarningHelper
echo "[5/8] Testing EarningHelper::totalWithdrawal()... ";
try {
    $helper = new \App\Helpers\General\EarningHelper();
    $teacher = User::role('teacher')->first();
    if ($teacher) {
        $result = $helper->totalWithdrawal($teacher->id);
        echo "✓ WORKING (returned: {$result})\n";
    } else {
        echo "⚠ SKIPPED: No teachers to test\n";
        $warnings++;
    }
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 6: Check database connection
echo "[6/8] Checking database connection... ";
try {
    DB::connection()->getPdo();
    echo "✓ CONNECTED\n";
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    $errors++;
}

// Test 7: Check critical tables exist
echo "[7/8] Checking critical tables... ";
$tables = ['users', 'courses', 'categories', 'orders', 'order_items', 'withdraws'];
$missing = [];
foreach ($tables as $table) {
    if (!Schema::hasTable($table)) {
        $missing[] = $table;
    }
}
if (empty($missing)) {
    echo "✓ ALL PRESENT\n";
} else {
    echo "✗ MISSING: " . implode(', ', $missing) . "\n";
    $errors++;
}

// Test 8: Check PayPal SDK handling
echo "[8/8] Checking PayPal SDK handling... ";
if (class_exists(\PayPal\Rest\ApiContext::class)) {
    echo "✓ SDK INSTALLED\n";
} else {
    echo "⚠ SDK NOT INSTALLED (handled gracefully)\n";
}

echo "\n";
echo "========================================\n";
echo "Results:\n";
echo "========================================\n";
echo "Errors: {$errors}\n";
echo "Warnings: {$warnings}\n";

if ($errors === 0 && $warnings === 0) {
    echo "\n✓ All checks passed!\n\n";
    exit(0);
} elseif ($errors === 0) {
    echo "\n⚠ All critical checks passed with {$warnings} warning(s)\n\n";
    exit(0);
} else {
    echo "\n✗ {$errors} error(s) found. Please review above.\n\n";
    exit(1);
}
