<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Category;

class CourseMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Find courses by category and title pattern
        $coursePatterns = [
            // Class 2
            ['class' => 2, 'subject' => 'Maths', 'title_pattern' => '%Maths%', 'exam' => 'IMO'],
            ['class' => 2, 'subject' => 'Science', 'title_pattern' => '%Science%', 'exam' => 'NSO'],
            ['class' => 2, 'subject' => 'English', 'title_pattern' => '%English%', 'exam' => 'IEO'],
            // Class 3
            ['class' => 3, 'subject' => 'Maths', 'title_pattern' => '%Maths%', 'exam' => 'IMO'],
            ['class' => 3, 'subject' => 'Science', 'title_pattern' => '%Science%', 'exam' => 'NSO'],
            ['class' => 3, 'subject' => 'English', 'title_pattern' => '%English%', 'exam' => 'IEO'],
            // Class 4
            ['class' => 4, 'subject' => 'Maths', 'title_pattern' => '%Maths%', 'exam' => 'IMO'],
            ['class' => 4, 'subject' => 'Science', 'title_pattern' => '%Science%', 'exam' => 'NSO'],
            ['class' => 4, 'subject' => 'English', 'title_pattern' => '%English%', 'exam' => 'IEO'],
            // Class 5
            ['class' => 5, 'subject' => 'Maths', 'title_pattern' => '%Maths%', 'exam' => 'IMO'],
            ['class' => 5, 'subject' => 'Science', 'title_pattern' => '%Science%', 'exam' => 'NSO'],
            ['class' => 5, 'subject' => 'English', 'title_pattern' => '%English%', 'exam' => 'IEO'],
            // Class 6
            ['class' => 6, 'subject' => 'Maths', 'title_pattern' => '%Maths%', 'exam' => 'IMO'],
            ['class' => 6, 'subject' => 'Science', 'title_pattern' => '%Science%', 'exam' => 'NSO'],
            ['class' => 6, 'subject' => 'English', 'title_pattern' => '%English%', 'exam' => 'IEO'],
            // Class 7
            ['class' => 7, 'subject' => 'Maths', 'title_pattern' => '%Maths%', 'exam' => 'IMO'],
            ['class' => 7, 'subject' => 'Science', 'title_pattern' => '%Science%', 'exam' => 'NSO'],
            ['class' => 7, 'subject' => 'English', 'title_pattern' => '%English%', 'exam' => 'IEO'],
            // Class 8
            ['class' => 8, 'subject' => 'Maths', 'title_pattern' => '%Maths%', 'exam' => 'IMO'],
            ['class' => 8, 'subject' => 'Science', 'title_pattern' => '%Science%', 'exam' => 'NSO'],
            ['class' => 8, 'subject' => 'English', 'title_pattern' => '%English%', 'exam' => 'IEO'],
        ];

        $updated = 0;
        $notFound = [];

        // First try to find courses by Olympiad category
        $olympiadCategory = Category::where('slug', 'like', '%olympiad%')->pluck('id')->toArray();
        
        foreach ($coursePatterns as $pattern) {
            $courses = Course::where('published', 1)
                ->whereIn('category_id', $olympiadCategory)
                ->where('title', 'like', "%Class {$pattern['class']}%")
                ->where('title', 'like', $pattern['title_pattern'])
                ->get();

            if ($courses->isEmpty()) {
                // Try broader search
                $courses = Course::where('published', 1)
                    ->where('title', 'like', "%Class {$pattern['class']}%")
                    ->where('title', 'like', $pattern['title_pattern'])
                    ->get();
            }

            if ($courses->isNotEmpty()) {
                foreach ($courses as $course) {
                    $metaTitle = "{$pattern['subject']} Olympiad for Class {$pattern['class']} - VaaGa Academy";
                    $metaDesc = "VaaGa Academy offers expert {$pattern['subject']} Olympiad coaching for Class {$pattern['class']} students. Prepare for {$pattern['exam']} exams with interactive live classes, mock tests, and personalized feedback.";
                    
                    $course->meta_title = $metaTitle;
                    $course->meta_description = $metaDesc;
                    $course->meta_keywords = $this->generateKeywords($pattern['subject'], $pattern['class']);
                    $course->save();
                    
                    $this->command->info("✓ Updated: {$course->title} (ID: {$course->id})");
                    $updated++;
                }
            } else {
                $notFound[] = "Class {$pattern['class']} - {$pattern['subject']}";
                $this->command->warn("✗ Not found: Class {$pattern['class']} - {$pattern['subject']}");
            }
        }

        $this->command->info("\n========================================");
        $this->command->info("Total Updated: {$updated}");
        
        if (!empty($notFound)) {
            $this->command->warn("Not Found: " . implode(', ', $notFound));
        }
    }

    /**
     * Generate SEO keywords based on subject and class
     */
    private function generateKeywords($subject, $classNum)
    {
        $keywords = [];
        
        switch ($subject) {
            case 'Maths':
                $keywords = [
                    "Maths Olympiad Class {$classNum}",
                    "IMO Class {$classNum}",
                    "Math Olympiad preparation",
                    "Maths Olympiad coaching online",
                    "IMO preparation Class {$classNum}",
                    "Mathematics Olympiad training",
                    "Maths Olympiad mock tests",
                ];
                break;
            case 'Science':
                $keywords = [
                    "Science Olympiad Class {$classNum}",
                    "NSO Class {$classNum}",
                    "Science Olympiad preparation",
                    "Science Olympiad coaching online",
                    "NSO preparation Class {$classNum}",
                    "Science Olympiad training",
                    "Science Olympiad mock tests",
                ];
                break;
            case 'English':
                $keywords = [
                    "English Olympiad Class {$classNum}",
                    "IEO Class {$classNum}",
                    "English Olympiad preparation",
                    "English Olympiad coaching online",
                    "IEO preparation Class {$classNum}",
                    "English Olympiad training",
                    "English Olympiad mock tests",
                ];
                break;
        }
        
        $commonKeywords = [
            "SOF Olympiad",
            "Olympiad exams Class {$classNum}",
            "Online Olympiad coaching",
            "Live Olympiad classes",
            "VaaGa Academy",
            "Best Olympiad coaching",
            "Olympiad study material",
        ];
        
        return implode(', ', array_merge($keywords, $commonKeywords));
    }
}
