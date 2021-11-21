<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Selective\TestTrait\Traits\ContainerTestTrait;
use Slim\App;
use UnexpectedValueException;

/**
 * The trait for app test.
 */
trait AppTestTrait
{
    use ContainerTestTrait;
    use DatabaseManagerTestTrait;

    /**
     * @var App The app.
     */
    protected App $app;

    /**
     * The set up testing environment method.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        $bootstrap = __DIR__ . '/../../config/bootstrap.php';

        if (!file_exists($bootstrap)) {
            throw new UnexpectedValueException("Bootstrap file doesn't exists.");
        }

        $this->app = require $bootstrap;
        $container = $this->app->getContainer();

        if ($container === null) {
            throw new UnexpectedValueException('Container must be initialized.');
        }

        // Set up container and database manager
        $this->setUpContainer($container);
        $this->setUpDatabaseManager($container);
    }

    /**
     * The teardown testing environment method.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
        $database = $this->dbm->database('default');

        // Delete all FKs first
        foreach ($database->getTables() as $table) {
            $schema = $table->getSchema();
            foreach ($schema->getForeignKeys() as $foreign) {
                $schema->dropForeignKey($foreign->getColumns());
            }

            $schema->save(\Spiral\Database\Driver\HandlerInterface::DROP_FOREIGN_KEYS);
        }

        // Delete tables
        foreach ($database->getTables() as $table) {
            $schema = $table->getSchema();
            $schema->declareDropped();
            $schema->save();
        }
    }
}
