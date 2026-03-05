<?php
/**
 * Debug Category Courses Relationship
 * Run: php debug_category_courses.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Course;

echo "========================================\n";
echo "DEBUG CATEGORY COURSES\n";
echo "========================================\n\n";

// Get Olympiad categories
$olympiadCats = Category::where('slug', 'like', '%olympiad%')->get();

echo "Found " . $olympiadCats->count() . " Olympiad categories:\n\n";

foreach ($olympiadCats as $cat) {
    echo "Category: {$cat->name} (ID: {$cat->id}, Slug: {$cat->slug})\n";
    echo "Parent: {$cat->parent}\n";
    
    // Count courses directly in this category
    $directCourses = Course::where('category_id', $cat->id)->where('published', 1)->count();
    echo "Direct Courses (published): {$directCourses}\n";
    
    // Get actual courses
    $courses = Course::where('category_id', $cat->id)->get();
    if ($courses->count() > 0) {
        echo "Course List:\n";
        foreach ($courses as $c) {
            echo "  - {$c->title} (ID: {$c->id}, Published: {$c->published})\n";
        }
    } else {
        echo "  (No courses found)\n";
    }
    
    echo "\n";
}

// Check all courses and their categories
echo "========================================\n";
echo "ALL COURSES WITH CATEGORIES:\n";
echo "========================================\n\n";

$courses = Course::with('category')->where('published', 1)->get();

echo "Total published courses: {$courses->count()}\n\n";

foreach ($courses as $c) {
    $catName = $c->category ? $c->category->name : 'NO CATEGORY';
    $catId = $c->category ? $c->category->id : 'N/A';
    echo "- {$c->title}\n";
    echo "  Category: {$catName} (ID: {$catId})\n\n";
}

echo "========================================\n";
echo "END OF DEBUG\n";
echo "========================================\n";
