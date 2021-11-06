<?php

use Spiral\Database\Driver\MySQL\MySQLDriver;

// Initialize settings
$settings = [];

// Database settings
$settings['database'] = [
    'default'     => 'default',
    'databases'   => [
        'default' => ['connection' => 'mysql']
    ],
    'connections' => [
        'mysql' => [
            'driver'  => MySQLDriver::class,
            'connection' => "mysql:host=localhost;dbname=slim-api-db",
            'username'   => "root",
            'password'   => "",
        ]
    ]
];

// Entities location
$settings['entity'] = __DIR__ . '/../src/Data/Entities';

return $settings;
