<?php
namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\Rule;


class Recaptcha implements Rule
{
    public function passes($attribute, $value)
    {
        
        // $response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
        //         'secret' => config('services.recaptcha.secret_key'),
        //         'response' => $value,
        // ]);
       
 
        // if ($response->successful() && $response->json('success') ) {
        //     return true;
        // }
 
        // return false; 
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
    $secretKey = config('services.recaptcha.secret_key'); // Replace this with your actual secret key

    $data = [
        'secret' => $secretKey,
        'response' => $value,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $responseDecoded = json_decode($response, true);

    if (isset($responseDecoded['success']) && $responseDecoded['success']) {
        return true;
    }

    return false;
    } 
 
    public function message()
    {
        return 'Verify that you are not a robot.';
    }
} 