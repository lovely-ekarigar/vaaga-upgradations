<?php
/**
 * Emergency Fix - Check and Fix Course Category Links
 * Run: php quick_fix_courses.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Course;

echo "========================================\n";
echo "EMERGENCY COURSES DEBUG & FIX\n";
echo "========================================\n\n";

// 1. Check Category 101 (Olympiad)
$cat101 = Category::find(101);
if ($cat101) {
    echo "Category 101: {$cat101->name} (Slug: {$cat101->slug})\n";
    
    // Count courses directly linked
    $directCount = Course::where('category_id', 101)->count();
    echo "Direct courses in cat_id=101: {$directCount}\n\n";
    
    if ($directCount == 0) {
        echo "❌ ISSUE: No courses directly linked to Olympiad category!\n\n";
        
        // Find child categories
        $children = Category::where('parent', 101)->get();
        echo "Child categories of Olympiad:\n";
        foreach ($children as $child) {
            $childCourses = Course::where('category_id', $child->id)->count();
            echo "  - {$child->name} (ID: {$child->id}): {$childCourses} courses\n";
        }
        echo "\n";
        
        // Find all Olympiad-related courses
        echo "Courses with 'Olympiad' in title:\n";
        $courses = Course::where('title', 'like', '%Olympiad%')->get();
        foreach ($courses as $c) {
            $cat = Category::find($c->category_id);
            $catName = $cat ? $cat->name : 'NO CAT';
            echo "  - {$c->title}\n";
            echo "    Current cat_id: {$c->category_id} ({$catName})\n\n";
        }
    }
} else {
    echo "Category 101 not found!\n";
}

echo "========================================\n";
echo "FIX OPTIONS:\n";
echo "========================================\n";
echo "1. Link courses directly to Olympiad category\n";
echo "2. Show courses from child categories in parent view\n";
echo "3. Find correct category IDs for child categories\n";
echo "========================================\n";
