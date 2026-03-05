<?php
/**
 * Direct Meta Update Script
 * Run: php update_meta_direct.php
 * 
 * This script updates course meta directly in the database
 * based on course titles (not slugs)
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;
use App\Models\Category;

echo "========================================\n";
echo "DIRECT META UPDATE SCRIPT\n";
echo "========================================\n\n";

// Course patterns to match
$patterns = [
    ['class' => 2, 'subject' => 'Maths', 'exam' => 'IMO'],
    ['class' => 2, 'subject' => 'Science', 'exam' => 'NSO'],
    ['class' => 2, 'subject' => 'English', 'exam' => 'IEO'],
    ['class' => 3, 'subject' => 'Maths', 'exam' => 'IMO'],
    ['class' => 3, 'subject' => 'Science', 'exam' => 'NSO'],
    ['class' => 3, 'subject' => 'English', 'exam' => 'IEO'],
    ['class' => 4, 'subject' => 'Maths', 'exam' => 'IMO'],
    ['class' => 4, 'subject' => 'Science', 'exam' => 'NSO'],
    ['class' => 4, 'subject' => 'English', 'exam' => 'IEO'],
    ['class' => 5, 'subject' => 'Maths', 'exam' => 'IMO'],
    ['class' => 5, 'subject' => 'Science', 'exam' => 'NSO'],
    ['class' => 5, 'subject' => 'English', 'exam' => 'IEO'],
    ['class' => 6, 'subject' => 'Maths', 'exam' => 'IMO'],
    ['class' => 6, 'subject' => 'Science', 'exam' => 'NSO'],
    ['class' => 6, 'subject' => 'English', 'exam' => 'IEO'],
    ['class' => 7, 'subject' => 'Maths', 'exam' => 'IMO'],
    ['class' => 7, 'subject' => 'Science', 'exam' => 'NSO'],
    ['class' => 7, 'subject' => 'English', 'exam' => 'IEO'],
    ['class' => 8, 'subject' => 'Maths', 'exam' => 'IMO'],
    ['class' => 8, 'subject' => 'Science', 'exam' => 'NSO'],
    ['class' => 8, 'subject' => 'English', 'exam' => 'IEO'],
];

$totalUpdated = 0;

foreach ($patterns as $pattern) {
    $classNum = $pattern['class'];
    $subject = $pattern['subject'];
    $exam = $pattern['exam'];
    
    // Find courses matching this pattern
    $courses = Course::where('title', 'like', "%Class {$classNum}%")
        ->where('title', 'like', "%{$subject}%")
        ->where('published', 1)
        ->get();
    
    echo "Class {$classNum} - {$subject}: Found " . $courses->count() . " courses\n";
    
    foreach ($courses as $course) {
        $metaTitle = "{$subject} Olympiad for Class {$classNum} - VaaGa Academy";
        $metaDesc = "VaaGa Academy offers expert {$subject} Olympiad coaching for Class {$classNum} students. Prepare for {$exam} exams with interactive live classes, mock tests, and personalized feedback.";
        
        // Generate keywords
        $keywords = [
            "{$subject} Olympiad Class {$classNum}",
            "{$exam} Class {$classNum}",
            "{$subject} Olympiad preparation",
            "{$subject} Olympiad coaching online",
            "{$exam} preparation Class {$classNum}",
            "{$subject} Olympiad training",
            "{$subject} Olympiad mock tests",
            "SOF Olympiad",
            "Olympiad exams Class {$classNum}",
            "Online Olympiad coaching",
            "Live Olympiad classes",
            "VaaGa Academy",
            "Best Olympiad coaching",
            "Olympiad study material",
        ];
        $metaKeywords = implode(', ', $keywords);
        
        // Update the course
        $course->meta_title = $metaTitle;
        $course->meta_description = $metaDesc;
        $course->meta_keywords = $metaKeywords;
        $course->save();
        
        echo "  ✓ Updated: {$course->title}\n";
        $totalUpdated++;
    }
}

echo "\n========================================\n";
echo "TOTAL COURSES UPDATED: {$totalUpdated}\n";
echo "========================================\n";

echo "\nClearing cache...\n";
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');
echo "Cache cleared!\n";
