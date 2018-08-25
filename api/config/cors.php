<?php


    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
return [
    'supportsCredentials' => true,
    'allowedOrigins' => [
      '*'
      /*
      'http://localhost',
      'http://localhost:3000',
      'http://localhost:3030',
      'http://localhost:4649',
      'http://localhost:5000',
      'http://control.pub.io',
      'http://pub.com',
      'https://pub.com',
       */
    ],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => ['Authorization'],
    'maxAge' => 0,
];
