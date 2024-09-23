<?php

return [
    'storage' => env('SETTINGS_STORAGE', 'mysql'),
    'default_tenant_id' => env('SETTINGS_DEFAULT_TENANT_ID', 0),
    'cache_enabled' => env('SETTINGS_CACHE_ENABLED', false),
    'cache_duration' => env('SETTINGS_CACHE_DURATION', 3600), // seconds
];
