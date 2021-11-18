<?php

declare(strict_types=1);

// Configure defaults for the whole application.
$root = dirname(__DIR__);

// Error reporting
error_reporting(0);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Load configuration based on environment
$dotenv = (Dotenv\Dotenv::createMutable($root))->safeLoad();
$env = '.env' . '.' . strtolower($_ENV['APP_ENVIRONMENT'] ?? '');
if (file_exists($root . '/' . $env)) {
    $dotenv = array_merge($dotenv, (Dotenv\Dotenv::createMutable($root, $env))->safeLoad());
}

// Timezone
date_default_timezone_set($_ENV['APP_TIMEZONE']);

// Initialize settings
$settings = [

    // Path settings
    'public'    => $root . '/public',
    'template'  => $root . '/templates',
    'entity'    => $root . '/src/Data/Entities',

    // Error handler settings
    'error' => [
        'display_error_details' => $_ENV['ERROR_DISPLAY_DETAILS'],
        'log_errors'            => $_ENV['ERROR_LOG'],
        'log_error_details'     => $_ENV['ERROR_LOG_DETAILS'],
    ],

    // Logger settings
    'logger' => [
        'name'              => $_ENV['LOGGER_NAME'],
        'path'              => $root . '/' . $_ENV['LOGGER_PATH'],
        'filename'          => $_ENV['LOGGER_FILENAME'],
        'level'             => $_ENV['LOGGER_DEBUG'] ? \Monolog\Logger::DEBUG : \Monolog\Logger::INFO,
        'file_permission'   => 0775,
    ],

    // Database settings
    'database' => [
        'default'     => 'default',
        'databases'   => [
            'default' => ['connection' => $_ENV['DB_CONNECTION']]
        ],
        'connections' => [
            'mysql' => [
                'driver'        => Spiral\Database\Driver\MySQL\MySQLDriver::class,
                'connection'    => "mysql:host={$_ENV['DB_HOST']}:{$_ENV['DB_PORT']};dbname={$_ENV['DB_DBNAME']}",
                'username'      => $_ENV['DB_USERNAME'],
                'password'      => $_ENV['DB_PASSWORD'],
            ]
        ]
    ]
];

return $settings;
