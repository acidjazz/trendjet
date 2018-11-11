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
    'key' => env('AWS_KEY'),
    'secret' => env('AWS_SECRET'),
    'region' => 'us-east-1',
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
    'api_key' => env('GOOGLE_API_KEY'),
    'client_id' => '393122115262-av4l4mjomlod4oc4rao0lv0m8o3fjqvm.apps.googleusercontent.com',
    'client_secret' => env('OAUTH_GOOGLE_SECRET'),
    'redirect' => env('APP_URL').'callback/google',
  ],

  'facebook' => [
    'client_id' => '554714374948367',
    'client_secret' => env('OAUTH_FACEBOOK_SECRET'),
    'redirect' => env('APP_URL').'callback/facebook',
  ],

];
