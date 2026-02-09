<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;


class WhiteboardController extends Controller
{
    private $accessKey = 'wbao0a-mmEZrOmmN';
    private $secretKey = 'URKro2XZr7seXm3tjjQAgr4khHmrcAbb';
    private $region = 'in-mum'; // Choose: in-mum, us-sv, sg, cn-hz

public function createRoomAndToken(Request $request)
{
    $userRole = $request->input('role', 'admin');
    $client = new Client();

    $payload = [
        'accessKey' => $this->accessKey,
        'lifespan' => 3600000,
        'role' => 'admin',
        'iat' => time(),
    ];

    // $sdkToken = JWT::encode($payload, $this->secretKey, 'HS256');
    $sdkToken="NETLESSSDK_YWs9d2JhbzBhLW1tRVpyT21tTiZub25jZT04M2RiZWViMC02YzhlLTExZjAtYTQzNS1jZjY0YzgwZDI3NTcmcm9sZT0wJnNpZz1iM2I3YjdkMjA0M2Y0MzdjYmEyMzE5OWQzYzcyNDNkZGVhNjkwYWRjNTdhZDdkNWQ4NzFhOTA2MjYzODU3YTY4";

    try {
        // Step 1: Create Whiteboard Room
        $createRoomResponse = $client->post('https://api.netless.link/v5/rooms', [
            'headers' => [
                'Content-Type' => 'application/json',
                'token' => $sdkToken,
                'region' => $this->region,
            ],
            'json' => [
                'isRecord' => false
            ]
        ]);

        $createRoomBody = json_decode($createRoomResponse->getBody(), true);
        $roomUUID = $createRoomBody['uuid'] ?? null;

        if (!$roomUUID) {
            return response()->json(['error' => 'Room UUID not found in response.'], 500);
        }

        // Step 2: Generate Room Token
        $roomTokenResponse = $client->post("https://api.netless.link/v5/tokens/rooms/{$roomUUID}", [
            'headers' => [
                'Content-Type' => 'application/json',
                'token' => $sdkToken,
                'region' => $this->region,
            ],
            'json' => [
                'lifespan' => 3600000,
                'role' => $userRole,
            ]
        ]);

        $roomTokenBody = json_decode($roomTokenResponse->getBody(), true);
        $roomToken = $roomTokenBody ?? null;
        // dd($roomTokenBody);

        if (!$roomToken) {
            return response()->json(['error' => 'Room token not found in response.'], 500);
        }

        return response()->json([
            'room_uuid' => $roomUUID,
            'room_token' => $roomToken,
            'sdk_token' => $sdkToken,
            'role' => $userRole,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Exception occurred', 'message' => $e->getMessage()], 500);
    }
}

    
  public function viewWhiteboard(Request $request)
{
    // Get whiteboard room/token
    $roomData = $this->createRoomAndToken($request)->getData(true);

    // Agora RTC App ID from .env
    $rtcAppId = "25406e1d9cff48b4b09981c123d91e5c";

    // Generate random RTC UID for this session
    $rtcUid = rand(100000, 999999);

    // Generate RTC token using your own logic or Agora token server (optional if token is not required)
    // For now, set null if not implemented
    $rtcToken = null;

    return view('whiteboard', [
        'appId' => $this->accessKey,
        'roomUUID' => $roomData['room_uuid'],
        'roomToken' => $roomData['room_token'],
        'region' => $this->region,
        'rtcAppId' => $rtcAppId,
        'rtcUid' => $rtcUid,
        'rtcToken' => $rtcToken, // optional
        'rtcChannel' => $roomData['room_uuid'], // using room UUID as channel name
    ]);
}


}
