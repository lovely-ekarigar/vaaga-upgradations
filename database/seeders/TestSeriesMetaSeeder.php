<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestSeries;

class TestSeriesMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Update Olympiad Test Series with meta data
        $testSeries = [
            [
                'name' => 'Olympiad',
                'slug' => 'olympiad',
                'meta_title' => 'Olympiad Test Series for Grade 2–8 - VaaGa Academy',
                'meta_description' => 'VaaGa Academy offers comprehensive Olympiad Test Series for Grade 2–8 students. Prepare for IMO, NSO & IEO exams with mock tests, performance tracking, and personalized feedback.',
                'meta_keywords' => 'Olympiad Test Series, Olympiad mock tests, IMO test series, NSO test series, IEO test series, Grade 2-8 Olympiad tests, Online Olympiad tests, Olympiad practice tests, SOF Olympiad preparation, VaaGa Academy tests, Olympiad exam practice, Maths Science English tests',
            ],
        ];

        $updated = 0;
        $notFound = [];

        foreach ($testSeries as $tsData) {
            // Try to find by slug first, then by name
            $ts = null;
            if (!empty($tsData['slug'])) {
                $ts = TestSeries::where('slug', $tsData['slug'])->first();
            }
            if (!$ts) {
                $ts = TestSeries::where('name', 'like', '%' . $tsData['name'] . '%')->first();
            }
            
            if ($ts) {
                $ts->meta_title = $tsData['meta_title'];
                $ts->meta_description = $tsData['meta_description'];
                $ts->meta_keywords = $tsData['meta_keywords'];
                if (empty($ts->slug) && !empty($tsData['slug'])) {
                    $ts->slug = $tsData['slug'];
                }
                $ts->save();
                
                $this->command->info("✓ Updated Test Series: {$tsData['name']}");
                $updated++;
            } else {
                $notFound[] = $tsData['name'];
                $this->command->warn("✗ Not found: {$tsData['name']}");
            }
        }

        $this->command->info("\n========================================");
        $this->command->info("Total Updated: {$updated}/" . count($testSeries));
        
        if (!empty($notFound)) {
            $this->command->warn("Not Found: " . implode(', ', $notFound));
        }
    }
}
