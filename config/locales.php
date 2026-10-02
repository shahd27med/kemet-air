<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | Used by the SetLocale middleware (to validate the stored/session
    | locale) and by the navbar language switcher (to render the toggle
    | and know which text direction to apply).
    |
    */

    'available' => [
        'en' => [
            'label' => 'English',
            'native' => 'English',
            'short' => 'EN',
            'dir' => 'ltr',
        ],
        'ar' => [
            'label' => 'Arabic',
            'native' => 'العربية',
            'short' => 'AR',
            'dir' => 'rtl',
        ],
    ],

];
