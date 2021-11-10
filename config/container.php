<?php

use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
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
use Slim\Views\PhpRenderer;
use Spiral\Database\{DatabaseManager};
use Spiral\Database\Config\{DatabaseConfig};
use Spiral\Database\Exception\{ConfigException};
use Spiral\Tokenizer\ClassLocator;
use Symfony\Component\Finder\Finder;

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

    // Php renderer definition with container interface injection
    PhpRenderer::class => function (ContainerInterface $container) {
        if (!isset($container->get('settings')['template'])) {
            throw new ConfigException('Expected template settings');
        }

        return new PhpRenderer($container->get('settings')['template']);
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

    // ORM definition with container interface injection
    ORM::class => function (ContainerInterface $container) {
        $settings = $container->has('settings')
            ? $container->get('settings')
            : [];

        if (!isset($settings['database'])) {
            throw new ConfigException('Expected database settings');
        }
        if (!isset($settings['entity'])) {
            throw new ConfigException('Expected entity settings');
        }

        $database = $container->get('settings')['database'];
        $entity = $container->get('settings')['entity'];

        $finder = (new Finder())->files()->in([$entity]);
        $classLocator = new ClassLocator($finder);
        $database = new DatabaseManager(new DatabaseConfig($database));
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

    // Repositories definition with user repository injection
    UserRepositoryInterface::class => DI\create(UserRepository::class)
        ->constructor(DI\get(ORM::class))
];
