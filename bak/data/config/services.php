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
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID','329621613521-optv5khpq1k43isn10oln6p5q38g489f.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET','t2ClL5hbPsjJ_Z_1sY0KRAdi'),
        'redirect'      => env('GOOGLE_REDIRECT','http://localhost:8000/redirect/google')
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID','340695283374933'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET','b4e19222a4985c97acb349a5508b6580'),
        'redirect' => env('FACEBOOK_REDIRECT','http://localhost:8000/redirect/facebook'),
    ],
    
    'linkedin' => [
        'client_id' => env('LINKEDIN_KEY','816mn53eo35qoq'),
        'client_secret' => env('LINKEDIN_SECRET','NLy4eygtvRkQn9mY'),
        'redirect' => env('LINKEDIN_REDIRECT_URI','http://localhost:8000/redirect/linkedin')
    ],


];
