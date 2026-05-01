<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use DB;

class GoogleController extends Controller
{
    //  STEP 1: Google Client Setup
    private function getClient()
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return $client;
    }

    //  STEP 2: Redirect to Google Login
    public function redirect()
    {
        
        $client = $this->getClient();
        return redirect($client->createAuthUrl());
    }

    //  STEP 3: Callback (SAVE TOKEN)
    public function callback(Request $request)
    {
        
        //   dd($request->all());
        $client = $this->getClient();
        $client->fetchAccessTokenWithAuthCode($request->code);

      $token = $client->getAccessToken();

   $existing = DB::table('google_tokens')
    ->where('user_id', auth()->id())
    ->first();

    DB::table('google_tokens')->updateOrInsert(
    ['user_id' => auth()->id()],
    [
        'access_token'  => $token['access_token'],

        //  IMPORTANT FIX (yahi change tha)
        'refresh_token' => $token['refresh_token'] ?? ($existing->refresh_token ?? null),

        'expires_in'    => $token['expires_in'],
        'expires_at'    => now()->addSeconds($token['expires_in']),
        'updated_at'    => now(),
        'created_at' => $existing->created_at ?? now(),
    ]
);

        return redirect('/')->with('success', 'Google Connected Successfully');
    }
    
    
    

    
public function generate(Request $request)
{
    try {

        if (!$request->start_time || !$request->end_time) {
            return response()->json(['error' => 'Start and End time required']);
        }

        if (!auth()->check()) {
            return response()->json(['error' => 'User not authenticated']);
        }

        $start = date('c', strtotime($request->start_time));
        $end   = date('c', strtotime($request->end_time));

        $link = $this->createMeetLink($start, $end);

        if (!$link) {
            return response()->json(['error' => 'Google link not generated']);
        }

        return response()->json(['link' => $link]);

    } catch (\Exception $e) {

        return response()->json([
            'error' => $e->getMessage()   //  IMPORTANT for debugging
        ]);
    }
}

public function status()
{
    $connected = DB::table('google_tokens')
        ->where('user_id', auth()->id())
        ->exists();

    return response()->json([
        'connected' => $connected
    ]);
}
    //  STEP 4: CREATE GOOGLE MEET LINK
  public function createMeetLink($startTime, $endTime)
{
    $client = $this->getClient();

    $tokenRow = DB::table('google_tokens')
        ->where('user_id', auth()->id())
        ->first();

    if (!$tokenRow) {
        throw new \Exception("No Google token found");
    }

    // fIX: proper token format
    $client->setAccessToken([
        'access_token'  => $tokenRow->access_token,
        'refresh_token' => $tokenRow->refresh_token,
        'expires_in'    => $tokenRow->expires_in,
        'created'       => strtotime($tokenRow->updated_at ?? now()),
    ]);

    // refresh if expired
    if ($client->isAccessTokenExpired()) {

        $newToken = $client->fetchAccessTokenWithRefreshToken($tokenRow->refresh_token);

        if (isset($newToken['error'])) {
            throw new \Exception($newToken['error_description'] ?? 'Token refresh failed');
        }

       DB::table('google_tokens')
    ->where('user_id', auth()->id())
    ->update([
        'access_token' => $newToken['access_token'],
        'expires_in'   => $newToken['expires_in'] ?? 3600,
        'expires_at'   => now()->addSeconds($newToken['expires_in'] ?? 3600),
        'updated_at'   => now(),
    ]);
        $client->setAccessToken($newToken);
    }

    $service = new Google_Service_Calendar($client);

  $event = new Google_Service_Calendar_Event([
    'summary' => 'Demo Session',

    'start' => [
        'dateTime' => $startTime,
        'timeZone' => 'Asia/Kolkata',
    ],
    'end' => [
        'dateTime' => $endTime,
        'timeZone' => 'Asia/Kolkata',
    ],

    //  IMPORTANT: Disable notifications
    'reminders' => [
        'useDefault' => false,
        'overrides' => []
    ],

    //  IMPORTANT: no attendees = no invite notifications
    'attendees' => [],

    'conferenceData' => [
        'createRequest' => [
            'requestId' => uniqid(),
            'conferenceSolutionKey' => [
                'type' => 'hangoutsMeet'
            ]
        ]
    ]
]);

    $event = $service->events->insert('primary', $event, [
        'conferenceDataVersion' => 1
    ]);

  return $event->conferenceData->entryPoints[0]->uri
    ?? $event->hangoutLink
    ?? null;
}
}