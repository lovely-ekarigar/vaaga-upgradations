<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class AiSensy extends Model
{
    protected $guarded = [];

    public static function send($payload)
    {
        $client = new Client();

        $url = config('app.aisensy_api_url', env('AISENSY_API_URL'));

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            
            //   dd($e->getMessage());
              
            // Optionally handle/log the error
            \Log::error('AiSensy send failed: ' . $e->getMessage());
            return false;
        }
    }
}
