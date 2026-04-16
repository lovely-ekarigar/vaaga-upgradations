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

        \Log::info('AiSensy API Request', [
            'url' => $url,
            'payload' => $payload
        ]);

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
               
            ]);

            $responseBody = json_decode($response->getBody(), true);
            $statusCode = $response->getStatusCode();
            
            \Log::info('AiSensy API Success', [
                'status_code' => $statusCode,
                'response' => $responseBody
            ]);

            return $responseBody;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorMessage = $e->getMessage();
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 'N/A';
            $responseBody = $e->hasResponse() ? (string) $e->getResponse()->getBody() : 'No response body';
            
            \Log::error('AiSensy send failed (RequestException)', [
                'error' => $errorMessage,
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'url' => $url,
                'payload' => $payload
            ]);
            
            return false;
        } catch (\Exception $e) {
            \Log::error('AiSensy send failed (General Exception)', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $url,
                'payload' => $payload
            ]);
            
            return false;
        }
    }
}
