<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VideoLink;

class VideoLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default video link for homepage
        VideoLink::firstOrCreate(
            ['id' => 1],
            [
                'link' => 'dQw4w9WgXcQ', // Default YouTube video ID
                'title' => 'Default Video',
                'description' => 'Default homepage video',
                'status' => 1
            ]
        );
    }
}
