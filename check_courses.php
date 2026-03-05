<?php
/**
 * Helper script to check existing courses and their slugs
 * Run: php check_courses.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;
use App\Models\Category;

echo "========================================\n";
echo "CHECKING COURSES AND META INFORMATION\n";
echo "========================================\n\n";

// Get all Olympiad categories
$olympiadCats = Category::where('slug', 'like', '%olympiad%')->get();
echo "Olympiad Categories Found: " . $olympiadCats->count() . "\n";
foreach ($olympiadCats as $cat) {
    echo "  - ID: {$cat->id}, Slug: {$cat->slug}, Name: {$cat->name}\n";
    echo "    Meta Title: " . ($cat->meta_title ?? 'EMPTY') . "\n";
    echo "    Meta Desc: " . ($cat->meta_description ? substr($cat->meta_description, 0, 50) . '...' : 'EMPTY') . "\n\n";
}

echo "\n========================================\n";
echo "COURSES IN OLYMPIAD CATEGORIES\n";
echo "========================================\n\n";

$catIds = $olympiadCats->pluck('id')->toArray();
$courses = Course::whereIn('category_id', $catIds)->where('published', 1)->get();

echo "Total Courses Found: " . $courses->count() . "\n\n";

foreach ($courses as $course) {
    echo "Course: {$course->title}\n";
    echo "  ID: {$course->id}\n";
    echo "  Slug: {$course->slug}\n";
    echo "  Meta Title: " . ($course->meta_title ?? 'EMPTY') . "\n";
    echo "  Meta Desc: " . ($course->meta_description ? substr($course->meta_description, 0, 50) . '...' : 'EMPTY') . "\n";
    echo "  Meta Keywords: " . ($course->meta_keywords ? substr($course->meta_keywords, 0, 50) . '...' : 'EMPTY') . "\n\n";
}

echo "\n========================================\n";
echo "DONE\n";
echo "========================================\n";
