<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'slug' => 'about',
                'title' => 'About Us',
                'meta_title' => 'VaaGa Academy - Online Olympiad Coaching for Students | About Us',
                'meta_description' => 'Learn more about VaaGa Academy\'s mission to provide expert online Olympiad coaching for Class 2–8 students. We specialize in Maths, Science & English coaching for IMO, NSO & IEO exams.',
                'meta_keywords' => 'About VaaGa Academy, Online Olympiad coaching, Olympiad coaching mission, IMO NSO IEO coaching, Maths Science English coaching, Best Olympiad academy, Online learning platform, Olympiad preparation India',
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'meta_title' => 'Contact VaaGa Academy - Get in Touch for Olympiad Coaching',
                'meta_description' => 'Have questions? Contact VaaGa Academy to learn more about our online Olympiad coaching for Class 2–8 students in Maths, Science & English. Prepare for IMO, NSO & IEO exams with expert guidance.',
                'meta_keywords' => 'Contact VaaGa Academy, Olympiad coaching contact, Online coaching inquiry, IMO NSO IEO coaching contact, Maths Science English coaching, Olympiad classes contact, Coaching center contact',
            ],
            [
                'slug' => 'become-tutor',
                'title' => 'Become a Tutor',
                'meta_title' => 'Become a Tutor at VaaGa Academy - Teach Online Olympiad Classes',
                'meta_description' => 'Join VaaGa Academy as an online tutor and teach Olympiad classes for Maths, Science & English. Share your expertise and help students prepare for IMO, NSO & IEO exams.',
                'meta_keywords' => 'Become a tutor, Online teaching jobs, Olympiad tutor, Teach Olympiad online, VaaGa Academy tutor, Online tutor jobs, Maths tutor, Science tutor, English tutor, Olympiad coaching jobs, Teaching opportunities',
            ],
        ];

        $updated = 0;
        $created = 0;
        $notFound = [];

        foreach ($pages as $pageData) {
            $page = Page::where('slug', $pageData['slug'])->first();
            
            if ($page) {
                // Update existing page
                $page->meta_title = $pageData['meta_title'];
                $page->meta_description = $pageData['meta_description'];
                $page->meta_keywords = $pageData['meta_keywords'];
                $page->save();
                
                $this->command->info("✓ Updated Page: {$pageData['slug']}");
                $updated++;
            } else {
                // Create new page if not exists
                try {
                    Page::create([
                        'slug' => $pageData['slug'],
                        'title' => $pageData['title'],
                        'meta_title' => $pageData['meta_title'],
                        'meta_description' => $pageData['meta_description'],
                        'meta_keywords' => $pageData['meta_keywords'],
                        'content' => '',
                        'published' => 1,
                        'user_id' => 1, // Default admin user
                    ]);
                    $this->command->info("✓ Created Page: {$pageData['slug']}");
                    $created++;
                } catch (\Exception $e) {
                    $notFound[] = $pageData['slug'] . " (Error: " . $e->getMessage() . ")";
                    $this->command->warn("✗ Failed: {$pageData['slug']}");
                }
            }
        }

        $this->command->info("\n========================================");
        $this->command->info("Total Updated: {$updated}");
        $this->command->info("Total Created: {$created}");
        
        if (!empty($notFound)) {
            $this->command->warn("Failed: " . implode(', ', $notFound));
        }
    }
}
