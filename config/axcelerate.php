<?php

// config for malpaso/LaravelAxcelerate
return [
    /*
    |--------------------------------------------------------------------------
    | Axcelerate API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for your Axcelerate instance. This should include the
    | protocol (https://) and domain, but not the /api path.
    |
    */
    'base_url' => env('AXCELERATE_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | API Authentication Tokens
    |--------------------------------------------------------------------------
    |
    | Your Axcelerate API requires two tokens for authentication:
    | - Web Service Token (wstoken)
    | - API Token (apitoken)
    |
    */
    'ws_token' => env('AXCELERATE_WS_TOKEN'),
    'api_token' => env('AXCELERATE_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the HTTP client used to make API requests.
    |
    */
    'timeout' => env('AXCELERATE_TIMEOUT', 30),
    'retry_attempts' => env('AXCELERATE_RETRY_ATTEMPTS', 3),
    'retry_delay' => env('AXCELERATE_RETRY_DELAY', 1000), // milliseconds

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Enable request/response logging for debugging purposes.
    |
    */
    'log_requests' => env('AXCELERATE_LOG_REQUESTS', false),
];
