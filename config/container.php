<?php

use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
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

    // PDO definition with container interface injection
    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];

        $pdo = new PDO(
            "{$settings['driver']}:
            host={$settings['host']};
            dbname={$settings['dbname']}",
            $settings['username'],
            $settings['password']
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    },

    // Repositories injection
    UserRepositoryInterface::class => DI\create(UserRepository::class)
        ->constructor(DI\get(PDO::class))
];
