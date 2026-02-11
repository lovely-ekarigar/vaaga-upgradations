<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tables = [
    'users', 'courses', 'batches', 'lessons', 'orders', 'subscriptions',
    'student_teacher_batches', 'teacher_batches', 'demo_requests', 'demo_feedbacks',
    'demo_histories', 'assignments', 'assignment_uploads', 'student_joins', 
    'subjective_exams', 'subjective_exam_uploads', 'student_feedback_questions', 
    'teacher_feedback_questions', 'feedbacks', 'mock_tests', 'mock_test_schedules', 
    'mock_test_results', 'mock_test_responses', 'tests', 'questions', 
    'questions_options', 'tests_results', 'tests_results_answers', 'question_reports', 
    'test_series', 'test_series_purchases', 'test_list', 'contacts', 'notifications', 
    'enquiries', 'sliders', 'testimonials', 'faqs', 'coupons', 'taxes',
    'categories', 'bundles', 'pages', 'blogs', 'blog_categories', 'sponsors', 
    'teams', 'teacher_payments', 'teacher_profiles', 'teacher_fees', 'earnings', 
    'withdraws', 'configs', 'locales', 'roles', 'permissions', 'model_has_roles',
    'model_has_permissions', 'btob', 'batch_exams', 'certificates', 'course_contents',
    'course_timeline', 'lession_complete', 'recordings', 'reviews',
    'teacher_attendances', 'update_themes', 'backups', 'menus', 'forum_categories',
    'forum_questions', 'forum_answers', 'achievements', 'marketing_leads',
    'marketing_campaigns', 'marketing_lists', 'lead_lists', 'lead_list_items',
    'on_signal_apps', 'api_clients', 'sitemaps', 'translations'
];

echo "=== DATABASE TABLES STATUS ===\n\n";
echo "TABLES WITH DATA:\n";
echo str_repeat("-", 50) . "\n";

$emptyTables = [];
$dataTables = [];
$notFoundTables = [];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        if ($count == 0) {
            $emptyTables[] = $table;
        } else {
            $dataTables[] = sprintf("%-35s %5d records", $table, $count);
        }
    } catch (Exception $e) {
        $notFoundTables[] = $table;
    }
}

// Show tables with data
foreach ($dataTables as $line) {
    echo $line . "\n";
}

echo "\n\nEMPTY TABLES (Need Data Import):\n";
echo str_repeat("-", 50) . "\n";
foreach ($emptyTables as $table) {
    echo "EMPTY: " . $table . "\n";
}

echo "\n\nTABLES NOT FOUND:\n";
echo str_repeat("-", 50) . "\n";
foreach ($notFoundTables as $table) {
    echo "NOT FOUND: " . $table . "\n";
}

echo "\n\n=== SUMMARY ===\n";
echo "Tables with data: " . count($dataTables) . "\n";
echo "Empty tables: " . count($emptyTables) . "\n";
echo "Not found: " . count($notFoundTables) . "\n";
