<?php

namespace App\Console;

use App\Console\Commands\Backup;
use App\Console\Commands\FixPermissions;
use App\Console\Commands\GenerateSitemap;
use App\Console\Commands\LessonTestChaterStudentsFix;
use App\Console\Commands\TeacherProfileFix;
use App\Console\Commands\UpdateRecordingLengths;
use App\Console\Commands\ActivateScheduledMockTests;
use App\Models\TeacherProfile;
use App\Models\MyExam;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Backup::class,
        GenerateSitemap::class,
        TeacherProfileFix::class,
        LessonTestChaterStudentsFix::class,
        UpdateRecordingLengths::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        if (config('backup_schedule') == 1) {
            $schedule->command(Backup::class)->daily();
        } elseif (config('backup_schedule') == 2) {
            $schedule->command(Backup::class)->weekly();

        } elseif (config('backup_schedule') == 3) {
            $schedule->command(Backup::class)->monthly();
        }


        if (config('sitemap.schedule') == 1) {

            $schedule->command(GenerateSitemap::class)->daily();

        } elseif (config('sitemap.schedule') == 2) {

            $schedule->command(GenerateSitemap::class)->weekly();

        } elseif (config('sitemap.schedule') == 3) {

            $schedule->command(GenerateSitemap::class)->monthly();

        }
        
        // Run the mock test activation command every minute
        $schedule->command('mock:activate-scheduled')->everyMinute();
        
        // Update recording lengths from BBB API every hour
        $schedule->command('recordings:update-lengths')->hourly();
        
        // Cleanup abandoned exams after 24 hours of inactivity
        $schedule->call(function () {
            try {
                $abandonedCount = MyExam::where('status', 'started')
                    ->where('last_ping', '<', time() - (24 * 60 * 60))
                    ->update(['status' => 'abandoned']);
                
                if ($abandonedCount > 0) {
                    Log::info("Marked {$abandonedCount} exam(s) as abandoned");
                }
            } catch (\Exception $e) {
                Log::error('Failed to cleanup abandoned exams: ' . $e->getMessage());
            }
        })->daily();
        
        // Refresh BBB meetings cache every 5 minutes during active hours (9 AM - 9 PM)
        $schedule->call(function () {
            try {
                $el = new \App\Models\Elearn();
                $meetings = $el->eClass("getMeetings", []);
                \Illuminate\Support\Facades\Cache::put('bbb_meetings', $meetings['meetings'] ?? [], 300);
            } catch (\Exception $e) {
                Log::error('Failed to cache BBB meetings: ' . $e->getMessage());
            }
        })->everyFiveMinutes()->between('9:00', '21:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
