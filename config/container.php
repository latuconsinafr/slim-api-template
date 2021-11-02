<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [
    // Application settings
    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    // Application definition with container interface injection
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $app = AppFactory::create();

        // Register routes
        (require __DIR__ . '/routes.php')($app);

        // Register middleware
        (require __DIR__ . '/middleware.php')($app);

        return $app;
    },
];
