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
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Selective\Validation\Encoder\JsonEncoder;
use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Selective\Validation\Transformer\ErrorDetailsResultTransformer;
use Slim\App;
use Slim\Factory\AppFactory;
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

    // Validation exception middleware definition with container interface injection
    ValidationExceptionMiddleware::class => function (ContainerInterface $container) {
        $factory = $container->get(ResponseFactoryInterface::class);

        return new ValidationExceptionMiddleware(
            $factory,
            new ErrorDetailsResultTransformer(),
            new JsonEncoder()
        );
    },

    // Response factory definition with container interface injection
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);

        return $app->getResponseFactory();
    },


    // Repositories definition with user repository injection
    UserRepositoryInterface::class => DI\create(UserRepository::class)
        ->constructor(DI\get(ORM::class))
];
