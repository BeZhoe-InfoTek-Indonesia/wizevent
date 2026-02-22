<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Toaster Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure the default options for the toaster component.
    |
    */

    'options' => [
        'position' => 'top-right',
        'timeout' => 5000,
        'close-button' => true,
        'progress-bar' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Toaster View
    |--------------------------------------------------------------------------
    |
    | Here you may specify which view file should be used for the toaster.
    |
    */

    'view' => 'toaster::toaster',

    /*
    |--------------------------------------------------------------------------
    | Toaster Duration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the default duration for toasts.
    |
    */

    'duration' => 5000,
];
