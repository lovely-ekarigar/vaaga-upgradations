<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            // Main Olympiad Category (Parent)
            [
                'slug' => 'olympiad',
                'meta_title' => 'Olympiad Online Coaching for Class 2–8 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad coaching for Class 2–8 students in Maths, Science & English. Prepare for IMO, NSO & IEO exams with live sessions, mock tests, and personalized feedback.',
                'class_num' => null,
                'is_main' => true
            ],
            [
                'slug' => 'olympiad-class-2',
                'meta_title' => 'Olympiad Classes for Class 2 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad classes for Class 2 in Maths, Science & English. Prepare for IMO, NSO & IEO exams with live classes, mock tests, and personalized feedback.',
                'class_num' => 2,
                'is_main' => false
            ],
            [
                'slug' => 'olympiad-class-3',
                'meta_title' => 'Olympiad Classes for Class 3 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad coaching for Class 3 students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.',
                'class_num' => 3,
                'is_main' => false
            ],
            [
                'slug' => 'olympiad-class-4',
                'meta_title' => 'Olympiad Classes for Class 4 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad coaching for Class 4 students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.',
                'class_num' => 4,
                'is_main' => false
            ],
            [
                'slug' => 'olympiad-class-5',
                'meta_title' => 'Olympiad Classes for Class 5 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad coaching for Class 5 students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.',
                'class_num' => 5,
                'is_main' => false
            ],
            [
                'slug' => 'olympiad-class-6',
                'meta_title' => 'Olympiad Classes for Class 6 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad coaching for Class 6 students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.',
                'class_num' => 6,
                'is_main' => false
            ],
            [
                'slug' => 'olympiad-class-7',
                'meta_title' => 'Olympiad Classes for Class 7 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad coaching for Class 7 students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.',
                'class_num' => 7,
                'is_main' => false
            ],
            [
                'slug' => 'olympiad-class-8',
                'meta_title' => 'Olympiad Classes for Class 8 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers expert Olympiad coaching for Class 8 students. Prepare for IMO, NSO & IEO exams with interactive live classes, mock tests, and personalized feedback.',
                'class_num' => 8,
                'is_main' => false
            ],
        ];

        $updated = 0;
        $notFound = [];

        foreach ($categories as $catData) {
            $category = Category::where('slug', $catData['slug'])->first();
            
            if ($category) {
                $category->meta_title = $catData['meta_title'];
                $category->meta_description = $catData['meta_description'];
                $category->meta_keyword = $this->generateKeywords($catData['class_num'], $catData['is_main']);
                $category->save();
                
                $this->command->info("✓ Updated: {$catData['slug']}");
                $updated++;
            } else {
                $notFound[] = $catData['slug'];
                $this->command->warn("✗ Not found: {$catData['slug']}");
            }
        }

        $this->command->info("\n========================================");
        $this->command->info("Total Updated: {$updated}/" . count($categories));
        
        if (!empty($notFound)) {
            $this->command->warn("Not Found: " . implode(', ', $notFound));
        }
    }

    /**
     * Generate SEO keywords based on class number
     */
    private function generateKeywords($classNum, $isMain = false)
    {
        if ($isMain) {
            return 'Olympiad coaching, Olympiad online classes, IMO preparation, NSO preparation, IEO preparation, Maths Olympiad, Science Olympiad, English Olympiad, Class 2-8 Olympiad, SOF Olympiad, VaaGa Academy, Live Olympiad classes, Olympiad mock tests, Online Olympiad coaching';
        }
        
        $keywords = [
            "Class {$classNum} Olympiad",
            "Class {$classNum} IMO",
            "Class {$classNum} NSO",
            "Class {$classNum} IEO",
            "Olympiad coaching Class {$classNum}",
            "Olympiad preparation Class {$classNum}",
            "Online Olympiad classes Class {$classNum}",
            "Maths Science English Olympiad Class {$classNum}",
            "SOF Olympiad Class {$classNum}",
            "VaaGa Academy Olympiad",
            "Best Olympiad coaching online",
            "Live Olympiad classes",
            "Olympiad mock tests Class {$classNum}",
        ];
        
        return implode(', ', $keywords);
    }
}
