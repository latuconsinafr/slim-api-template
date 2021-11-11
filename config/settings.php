<?php

// Configure defaults for the whole application.

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Initialize settings
$settings = [];

// Path settings
$settings['root'] = dirname(__DIR__);
$settings['public'] = $settings['root'] . '/public';
$settings['template'] = $settings['root'] . '/templates';
$settings['entity'] = $settings['root'] . '/src/Data/Entities';

// Error handler settings
$settings['error'] = [
    'display_error_details' => true,
    'log_errors' => true,
    'log_error_details' => true,
];

// Logger settings
$settings['logger'] = [
    'name' => 'app',
    'path' => $settings['root'] . '/logs',
    'filename' => 'app.log',
    'level' => \Monolog\Logger::INFO,
    'file_permission' => 0775,
];

// Database settings
$settings['database'] = [
    'default'     => 'default',
    'databases'   => [
        'default' => ['connection' => 'mysql']
    ],
    'connections' => [
        'mysql' => [
            'driver'  => Spiral\Database\Driver\MySQL\MySQLDriver::class,
            'connection' => "mysql:host=localhost;dbname=slim-api-db",
            'username'   => "root",
            'password'   => "",
        ]
    ]
];

return $settings;
