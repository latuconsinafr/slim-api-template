<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';

use Cycle\ORM\ORM;
use Psr\Container\ContainerInterface;
use Spiral\Database\DatabaseManager;

/**
 * The generic seeder.
 */
final class Seeder
{
    /**
     * @var ContainerInterface The container interface.
     */
    private ContainerInterface $container;

    /**
     * @var DatabaseManager The database manager.
     */
    private DatabaseManager $dbm;

    /**
     * The constructor
     */
    public function __construct()
    {
        // Get the app bootstrap
        $bootstrap = __DIR__ . '/../../config/bootstrap.php';

        if (!file_exists($bootstrap)) {
            throw new UnexpectedValueException("Bootstrap file doesn't exists.");
        }

        $app = require $bootstrap;
        $this->container = $app->getContainer();

        if ($this->container === null) {
            throw new UnexpectedValueException('Container must be initialized.');
        }

        // Get the database manager and set cycle orm
        $this->dbm = $this->container->get(DatabaseManager::class);
    }

    /** 
     * The run seeder method.
     * 
     * @param bool $cleanFirst Proceed the seeder with initiate the database cleaner first.
     * 
     * @return void
     */
    public function run(bool $cleanFirst = false): void
    {
        try {
            // Clean the database first
            if ($cleanFirst) {
                $this->clean();
            }
            $this->container->get(ORM::class);

            // Get the initial data
            $initialData = __DIR__ . '/../../resources/setup/initialdata.json';

            if (!file_exists($initialData)) {
                throw new UnexpectedValueException('Initial data file not found.');
            }

            $initialData = json_decode(file_get_contents($initialData), true);

            // Seeding the database with initial data
            foreach ($initialData as $table => $data) {
                $insert = $this->dbm->database('default')->insert((string)$table);
                foreach ($data as $value) {
                    $value['created_at'] = new \DateTimeImmutable();
                    $value['updated_at'] = new \DateTimeImmutable();

                    $insert->values($value);
                }
                $insert->run();
            }

            echo 'Database successfully seeded.';
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Clean database method.
     * 
     * @return void
     */
    public function clean(): void
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

$seeder = new Seeder();
$seeder->run();
