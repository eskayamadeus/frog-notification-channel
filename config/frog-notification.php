<?php

return [
    'api_key' => env('FROG_API_KEY'),
    'username' => env('FROG_USERNAME'),
    'sender_id' => env('FROG_SENDER_ID'),

    /**
     * Force all messages to this contact when in development.
     */
    'debugging_contact' => env('FROG_DEBUGGING_CONTACT'),

    /**
     * Ignore actual numbers in development. To be used with "debug_to"
     */
    'development_mode' => env('FROG_DEVELOPMENT_MODE'),

    // Base URL for FROG without ending slash
    'base_url' => env('FROG_BASE_URL', 'https://frogapi.wigal.com.gh/api/v3')
];