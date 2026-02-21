<?php

return [
    'api' => [
        'base_url' => env('KORI_API_BASE_URL', 'http://127.0.0.1:8081'),
        'timeout'  => (int) env('KORI_API_TIMEOUT', 10),
        'retry' => [
            'times' => (int) env('KORI_API_RETRY_TIMES', 2),
            'sleep_ms' => (int) env('KORI_API_RETRY_SLEEP_MS', 200),
        ],
    ],

    'keycloak' => [
        'base_url'      => env('KEYCLOAK_BASE_URL', 'http://127.0.0.1:8080'),
        'realm'         => env('KEYCLOAK_REALM', 'kori'),
        'client_id'     => env('KEYCLOAK_CLIENT_ID', 'kori-portal'),
        'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),

        'redirect_uri'  => env('KEYCLOAK_REDIRECT_URI', 'http://127.0.0.1:8000/auth/callback'),
        'post_logout_redirect_uri' => env('KEYCLOAK_POST_LOGOUT_REDIRECT_URI', 'http://127.0.0.1:8000/login'),
    ],
];