<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\Auth\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
         
       
     
    ], 

    /*
     * Socialite Credentials
     * Redirect URL's need to be the same as specified on each network you set up this application on
     * as well as conform to the route:
     * http://localhost/public/login/SERVICE
     * Where service can github, facebook, twitter, google, linkedin, or bitbucket
     * Docs: https://github.com/laravel/socialite
     * Make sure 'scopes' and 'with' are arrays, if their are none, use empty arrays []
     */
    'bitbucket' => [
        'active'        => env('BITBUCKET_ACTIVE'),
        'client_id'     => env('BITBUCKET_CLIENT_ID'),
        'client_secret' => env('BITBUCKET_CLIENT_SECRET'),
        'redirect'      => env('BITBUCKET_REDIRECT'),
        'scopes'        => [],
        'with'          => [],
    ],

    'facebook' => [
        'active'        => env('FACEBOOK_ACTIVE'),
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT'),
        'scopes'        => [],
        'with'          => [],
        'fields'        => [],
    ],

    'github' => [
        'active'        => env('GITHUB_ACTIVE'),
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => env('GITHUB_REDIRECT'),
        'scopes'        => [],
        'with'          => [],
    ],

    'google' => [
        'active'        => env('GOOGLE_ACTIVE'),
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT'),

        /*
         * Only allows google to grab email address
         * Default scopes array also has: 'https://www.googleapis.com/auth/plus.login'
         * https://medium.com/@njovin/fixing-laravel-socialite-s-google-permissions-2b0ef8c18205
         */
        'scopes' => [
            'https://www.googleapis.com/auth/plus.me',
            'https://www.googleapis.com/auth/plus.profile.emails.read',
        ],

        'with' => [],
    ],

    'linkedin' => [
        'active'        => env('LINKEDIN_ACTIVE'),
        'client_id'     => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect'      => env('LINKEDIN_REDIRECT'),
        'scopes'        => [],
        'with'          => [],
        'fields'        => [],
    ],

    'twitter' => [
        'active'        => env('TWITTER_ACTIVE'),
        'client_id'     => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => env('TWITTER_REDIRECT'),
        'scopes'        => [],
        'with'          => [],
    ],
    
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'min_score' => env('RECAPTCHA_MIN_SCORE', .5),
    ],

    'msg91' => [
        'authkey' => env('MSG91_AUTHKEY', '401284ApGWjkfa66b6263aP1'),
        'template_id' => env('MSG91_OTP_TEMPLATE_ID', '64bc0cc6d6fc0517d05b6562'),
        'sender_id' => env('MSG91_SENDER_ID', 'VAAAGA'),
        'api_url' => env('MSG91_API_URL', 'https://control.msg91.com/api/v5/flow'),
        'enabled' => env('MSG91_ENABLED', true),
    ],

    'bigbluemeeting' => [
        'url' => env('BBB_API_URL', 'https://manager.bigbluemeeting.com'),
        'key' => env('BBB_API_KEY', '53iZbj6YMUJsKNhgoXC3kn1o2Vg1fBV5gQyqFiCgl0'),
    ],
];
