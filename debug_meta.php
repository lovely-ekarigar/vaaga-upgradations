<?php
/**
 * Debug Script - Check Course Meta in Database
 * Run: php debug_meta.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;
use App\Models\Category;

echo "========================================\n";
echo "DEBUGGING META INFORMATION\n";
echo "========================================\n\n";

// Check the specific course from the screenshot
$slug = 'olympiad-class-2-class-2-maths-olympiad-level-2';
$course = Course::where('slug', $slug)->first();

if ($course) {
    echo "COURSE FOUND:\n";
    echo "ID: {$course->id}\n";
    echo "Title: {$course->title}\n";
    echo "Slug: {$course->slug}\n";
    echo "Meta Title: " . ($course->meta_title ?? 'NULL/EMPTY') . "\n";
    echo "Meta Description: " . ($course->meta_description ?? 'NULL/EMPTY') . "\n";
    echo "Meta Keywords: " . ($course->meta_keywords ?? 'NULL/EMPTY') . "\n";
    echo "Published: {$course->published}\n\n";
} else {
    echo "COURSE NOT FOUND with slug: {$slug}\n\n";
}

// Find courses with "Class 2" and "Maths" in title
echo "Searching for courses with 'Class 2' and 'Maths' in title...\n";
$courses = Course::where('title', 'like', '%Class 2%')
    ->where('title', 'like', '%Maths%')
    ->get();

echo "Found: " . $courses->count() . " courses\n\n";

foreach ($courses as $c) {
    echo "- ID: {$c->id}, Title: {$c->title}\n";
    echo "  Slug: {$c->slug}\n";
    echo "  Meta Title: " . ($c->meta_title ? 'SET' : 'EMPTY') . "\n";
    echo "  Meta Desc: " . ($c->meta_description ? 'SET' : 'EMPTY') . "\n";
    echo "  Meta Keywords: " . ($c->meta_keywords ? 'SET' : 'EMPTY') . "\n\n";
}

// Show ALL Olympiad courses
echo "========================================\n";
echo "ALL OLYMPIAD COURSES (first 10):\n";
echo "========================================\n\n";

$allCourses = Course::where('title', 'like', '%Olympiad%')
    ->orWhere('slug', 'like', '%olympiad%')
    ->limit(10)
    ->get();

foreach ($allCourses as $c) {
    echo "- ID: {$c->id}, Title: {$c->title}\n";
    echo "  Slug: {$c->slug}\n";
    echo "  Published: {$c->published}\n";
    echo "  Meta Title: " . ($c->meta_title ? substr($c->meta_title, 0, 50) . '...' : 'EMPTY') . "\n";
    echo "  Meta Desc: " . ($c->meta_description ? substr($c->meta_description, 0, 50) . '...' : 'EMPTY') . "\n\n";
}

echo "========================================\n";
echo "END OF DEBUG\n";
echo "========================================\n";
