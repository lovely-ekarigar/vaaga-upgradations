<?php

namespace App\Services;

use App\Models\Auth\User;
use App\Models\MockTest;
use App\Models\MockTestSchedule;
use App\Models\MockTestResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AISensyService
{
    protected $apiUrl;
    protected $apiKey;
    protected $campaignId;
    protected $enabled;

    public function __construct()
    {
        $this->apiUrl = config('aisensy.api_url');
        $this->apiKey = config('aisensy.api_key');
        $this->campaignId = config('aisensy.campaign_id');
        $this->enabled = config('aisensy.enabled', false);
    }

    /**
     * Send WhatsApp message via AISensy
     */
    protected function sendWhatsAppMessage($phoneNumber, $templateName, $parameters = [])
    {
        if (!$this->enabled) {
            Log::info('AISensy is disabled. Skipping WhatsApp notification.');
            return false;
        }

        if (!$this->apiUrl || !$this->apiKey) {
            Log::warning('AISensy credentials not configured. Skipping WhatsApp notification.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'campaign_id' => $this->campaignId,
                'phone_number' => $phoneNumber,
                'template_name' => $templateName,
                'parameters' => $parameters,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully via AISensy', [
                    'phone' => $phoneNumber,
                    'template' => $templateName
                ]);
                return true;
            } else {
                Log::error('AISensy API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('AISensy service exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send mock test assignment notification
     */
    public function sendMockTestAssignment($userId, MockTest $mockTest)
    {
        $user = User::find($userId);
        if (!$user || !$user->phone_number) {
            return false;
        }

        return $this->sendWhatsAppMessage(
            $user->phone_number,
            'mock_test_assigned',
            [
                'student_name' => $user->name,
                'test_name' => $mockTest->title,
            ]
        );
    }

    /**
     * Send mock test scheduled notification
     */
    public function sendMockTestScheduled($userId, MockTestSchedule $schedule)
    {
        $user = User::find($userId);
        if (!$user || !$user->phone_number) {
            return false;
        }

        return $this->sendWhatsAppMessage(
            $user->phone_number,
            'mock_test_scheduled',
            [
                'student_name' => $user->name,
                'test_name' => $schedule->mockTest->title,
                'scheduled_date' => $schedule->scheduled_date->format('d M Y'),
            ]
        );
    }

    /**
     * Send mock test rescheduled notification
     */
    public function sendMockTestRescheduled($userId, MockTestSchedule $schedule, $reason)
    {
        $user = User::find($userId);
        if (!$user || !$user->phone_number) {
            return false;
        }

        return $this->sendWhatsAppMessage(
            $user->phone_number,
            'mock_test_rescheduled',
            [
                'student_name' => $user->name,
                'test_name' => $schedule->mockTest->title,
                'new_date' => $schedule->scheduled_date->format('d M Y'),
                'reason' => $reason,
            ]
        );
    }

    /**
     * Send mock test result notification
     */
    public function sendMockTestResult($userId, MockTestResult $result)
    {
        $user = User::find($userId);
        if (!$user || !$user->phone_number) {
            return false;
        }

        return $this->sendWhatsAppMessage(
            $user->phone_number,
            'mock_test_result',
            [
                'student_name' => $user->name,
                'test_name' => $result->schedule->mockTest->title,
                'score' => $result->total_correct,
                'total' => $result->total_questions,
                'percentage' => $result->percentage,
            ]
        );
    }
}
