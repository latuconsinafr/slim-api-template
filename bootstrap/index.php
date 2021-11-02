<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

// Initialize DI container instance
$containerBuilder = new ContainerBuilder();

// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');

// Build DI container instance
$container = $containerBuilder->build();

// Create App instance
return $container->get(App::class);
