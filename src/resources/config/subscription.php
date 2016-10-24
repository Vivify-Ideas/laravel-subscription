<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Subscription Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for subscription services such
    | as 2Checkout, and others. This file provides a sane default location
    | for this type of information, allowing packages to have a
    | conventional place to find your various credentials.
    |
    */
    'services' => [

        'TwoCheckout' => [
            'sid' => '',
            'secret' => '',
            'publishable_key' => '',
            'currency' => 'USD',
            'base_url' => 'https://www.2checkout.com/checkout/api/'
        ]

    ]

];
