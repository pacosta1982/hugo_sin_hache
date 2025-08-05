<?php

return [
    /*
     * ------------------------------------------------------------------------
     * Firebase project configuration
     * ------------------------------------------------------------------------
     */
    'projects' => [
        'app' => [
            /*
             * ------------------------------------------------------------------------
             * Firebase Project ID
             * ------------------------------------------------------------------------
             */
            'project_id' => env('FIREBASE_PROJECT_ID'),

            /*
             * ------------------------------------------------------------------------
             * Firebase Credentials (Service Account)
             * ------------------------------------------------------------------------
             */
            'credentials' => env('FIREBASE_CREDENTIALS'),

            /*
             * ------------------------------------------------------------------------
             * Firebase Database URL
             * ------------------------------------------------------------------------
             * 
             * Only required if using Firebase Realtime Database
             */
            'database' => [
                'url' => env('FIREBASE_DATABASE_URL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth settings
             * ------------------------------------------------------------------------
             */
            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Storage settings
             * ------------------------------------------------------------------------
             */
            'storage' => [
                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase library can cache authentication tokens to reduce
             * the number of API calls to Firebase. By default, tokens are
             * cached in memory and will be cleared when the PHP process ends.
             */
            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable debug logging for Firebase API calls.
             */
            'debug' => env('FIREBASE_DEBUG', false),

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             */
            'http_client_options' => [
                'timeout' => env('FIREBASE_HTTP_TIMEOUT', 30),
            ],
        ],
    ],
];