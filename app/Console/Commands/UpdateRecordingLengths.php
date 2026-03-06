<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recording;
use App\Models\Elearn;
use Illuminate\Support\Facades\Log;

class UpdateRecordingLengths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordings:update-lengths 
                            {--limit=50 : Maximum number of recordings to process}
                            {--force : Process even if recently checked}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update recording lengths and metadata from BigBlueButton API';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting recording length update...');
        
        try {
            // Get recordings that need updating
            $query = Recording::whereNull('recording_date')
                ->orWhere('recording_date', '')
                ->orWhere(function ($q) {
                    // Also check recordings from last 7 days that might have been updated
                    $q->where('created_at', '>=', now()->subDays(7))
                      ->whereNull('length');
                });
            
            if (!$this->option('force')) {
                // Skip if checked in last hour (unless force flag)
                $query->where(function ($q) {
                    $q->whereNull('updated_at')
                      ->orWhere('updated_at', '<=', now()->subHour());
                });
            }
            
            $recordings = $query->limit($this->option('limit'))->get();
            
            if ($recordings->isEmpty()) {
                $this->info('No recordings need updating.');
                return Command::SUCCESS;
            }
            
            $this->info("Found {$recordings->count()} recording(s) to process.");
            
            $el = new Elearn();
            $updated = 0;
            $failed = 0;
            $skipped = 0;
            
            $progressBar = $this->output->createProgressBar($recordings->count());
            $progressBar->start();
            
            foreach ($recordings as $recording) {
                try {
                    // Skip if no api_class_id
                    if (empty($recording->api_class_id)) {
                        $skipped++;
                        $progressBar->advance();
                        continue;
                    }
                    
                    // Call BBB API
                    $meetInfo = $el->eClass("getRecordings", [
                        'meetingID' => $recording->api_class_id
                    ]);
                    
                    // Check for valid response
                    if (!isset($meetInfo['returncode']) || $meetInfo['returncode'] !== 'SUCCESS') {
                        $failed++;
                        Log::warning('BBB API returned non-success', [
                            'recording_id' => $recording->id,
                            'api_class_id' => $recording->api_class_id,
                            'response' => $meetInfo
                        ]);
                        $progressBar->advance();
                        continue;
                    }
                    
                    // Handle different response formats
                    $recordingsData = $meetInfo['recordings'] ?? [];
                    if (isset($recordingsData['recording'])) {
                        // Single recording
                        $recordingData = $recordingsData['recording'];
                        if (isset($recordingData[0])) {
                            // Multiple recordings - take the latest
                            $recordingData = $recordingData[0];
                        }
                        
                        $updateData = [];
                        
                        // Extract length from playback format
                        if (isset($recordingData['playback']['format']['length'])) {
                            $updateData['length'] = $recordingData['playback']['format']['length'];
                        } elseif (isset($recordingData['playback']['format'][0]['length'])) {
                            $updateData['length'] = $recordingData['playback']['format'][0]['length'];
                        }
                        
                        // Extract timestamps
                        if (isset($recordingData['startTime'])) {
                            $updateData['startTime'] = $recordingData['startTime'];
                            $updateData['recording_date'] = date("Y-m-d", $recordingData['startTime'] / 1000);
                        }
                        
                        if (isset($recordingData['endTime'])) {
                            $updateData['endTime'] = $recordingData['endTime'];
                        }
                        
                        // Extract playback URL if available
                        if (isset($recordingData['playback']['format']['url'])) {
                            $updateData['view_url'] = $recordingData['playback']['format']['url'];
                        } elseif (isset($recordingData['playback']['format'][0]['url'])) {
                            $updateData['view_url'] = $recordingData['playback']['format'][0]['url'];
                        }
                        
                        // Update the record
                        if (!empty($updateData)) {
                            $recording->update($updateData);
                            $updated++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        $skipped++;
                    }
                    
                } catch (\Exception $e) {
                    $failed++;
                    Log::error('Failed to update recording length', [
                        'recording_id' => $recording->id,
                        'api_class_id' => $recording->api_class_id,
                        'error' => $e->getMessage()
                    ]);
                }
                
                $progressBar->advance();
                
                // Small delay to avoid overwhelming the BBB API
                usleep(100000); // 100ms
            }
            
            $progressBar->finish();
            $this->newLine();
            
            $this->info("✓ Updated: {$updated}");
            $this->info("○ Skipped: {$skipped}");
            $this->info("✗ Failed: {$failed}");
            
            Log::info('Recording length update completed', [
                'updated' => $updated,
                'skipped' => $skipped,
                'failed' => $failed
            ]);
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Command failed: ' . $e->getMessage());
            Log::error('Recording length update command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }
}
