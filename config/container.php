<?php

declare(strict_types=1);

use App\Services\UserService;
use App\Supports\Handlers\ErrorHandler;
use App\Supports\Loggers\Logger;
use Cycle\Annotated\{Embeddings, Entities, MergeColumns, MergeIndexes};
use Cycle\ORM\{Factory, ORM, Schema as OrmSchema};
use Cycle\Schema\{Compiler, Registry};
use Cycle\Schema\Generator\{
    GenerateRelations,
    GenerateTypecast,
    RenderRelations,
    RenderTables,
    ResetTables,
    SyncTables,
    ValidateEntities
};
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Selective\Validation\Encoder\JsonEncoder;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Selective\Validation\Transformer\ErrorDetailsResultTransformer;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\PhpRenderer;
use Spiral\Database\{DatabaseManager};
use Spiral\Database\Config\{DatabaseConfig};
use Spiral\Database\Exception\{ConfigException};
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;

return [

    // Application settings
    'settings' => function () {
        $settings = __DIR__ . '/settings.php';
        if (!file_exists($settings)) throw new ConfigException('settings.php does not exist.');

        return require $settings;
    },

    // Application definition with container interface injection
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $app = AppFactory::create();

        // Register routes
        $routes = __DIR__ . '/routes.php';
        if (!file_exists($routes)) throw new ConfigException('routes.php does not exist.');
        (require $routes)($app);

        // Register middleware
        $middleware = __DIR__ . '/middleware.php';
        if (!file_exists($middleware)) throw new ConfigException('middleware.php does not exist.');
        (require $middleware)($app);

        return $app;
    },

    // Http message factory definition with container interface injection
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    },
    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },
    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    // Route parser definition with container interface injection
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    // Validation exception middleware definition with container interface injection
    ValidationExceptionMiddleware::class => function (ContainerInterface $container) {
        $factory = $container->get(ResponseFactoryInterface::class);

        return new ValidationExceptionMiddleware(
            $factory,
            new ErrorDetailsResultTransformer(),
            new JsonEncoder()
        );
    },

    // The logger definition with container interface injection
    Logger::class => function (ContainerInterface $container) {
        if (!isset($container->get('settings')['logger'])) throw new ConfigException('Expected logger settings.');

        return new Logger($container->get('settings')['logger']);
    },

    // Php renderer definition with container interface injection
    PhpRenderer::class => function (ContainerInterface $container) {
        if (!isset($container->get('settings')['template'])) throw new ConfigException('Expected template settings.');

        return new PhpRenderer($container->get('settings')['template']);
    },

    // Error middleware definition with container interface injection
    ErrorMiddleware::class => function (ContainerInterface $container) {
        if (!isset($container->get('settings')['error'])) throw new ConfigException('Expected error settings.');

        $settings = $container->get('settings')['error'];
        $app = $container->get(App::class);

        $errorMiddleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)filter_var($settings['display_error_details'], FILTER_VALIDATE_BOOLEAN),
            (bool)filter_var($settings['log_errors'], FILTER_VALIDATE_BOOLEAN),
            (bool)filter_var($settings['log_error_details'], FILTER_VALIDATE_BOOLEAN)
        );

        $errorMiddleware->setDefaultErrorHandler($container->get(ErrorHandler::class));

        return $errorMiddleware;
    },

    // Database manager definition with container interface injection
    DatabaseManager::class => function (ContainerInterface $container) {
        if (!isset($container->get('settings')['database'])) throw new ConfigException('Expected database settings.');

        return new DatabaseManager(new DatabaseConfig($container->get('settings')['database']));
    },

    // ORM definition with container interface injection
    ORM::class => function (ContainerInterface $container) {
        if (!isset($container->get('settings')['entity'])) throw new ConfigException('Expected entity settings.');

        $finder = (new Finder())->files()->in([$container->get('settings')['entity']]);
        $classLocator = new ClassLocator($finder);
        $database = $container->get(DatabaseManager::class);
        $schema = (new Compiler())->compile(new Registry($database), [
            new ResetTables(),              // re-declared table schemas (remove columns)
            new Embeddings($classLocator),  // register embeddable entities
            new Entities($classLocator),    // register annotated entities
            new MergeColumns(),             // add @Table column declarations
            new GenerateRelations(),        // generate entity relations
            new ValidateEntities(),         // make sure all entity schemas are correct
            new RenderTables(),             // declare table schemas
            new RenderRelations(),          // declare relation keys and indexes
            new MergeIndexes(),             // add @Table column declarations
            new SyncTables(),               // sync table changes to database
            new GenerateTypecast(),         // typecast non string columns
        ]);
        $orm = new ORM(new Factory($database));
        $orm = $orm->withSchema(new OrmSchema($schema));

        return $orm;
    },

    // Services definition with ORM and Logger injection
    UserService::class => DI\create()->constructor(DI\get(ORM::class), DI\get(Logger::class))
];
