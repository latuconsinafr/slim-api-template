<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Cycle\ORM\ORM;
use Psr\Container\ContainerInterface;
use Spiral\Database\DatabaseManager;
use UnexpectedValueException;

/**
 * The trait for database test.
 */
trait DatabaseManagerTestTrait
{
    /**
     * @var DatabaseManager The database manager.
     */
    protected DatabaseManager $dbm;

    /**
     * The set up database manager method.
     * 
     * @param ContainerInterface $container The container interface.
     * 
     * @return void
     */
    public function setUpDatabaseManager(ContainerInterface $container): void
    {
        try {
            // Get the database manager and cycle orm
            $this->dbm = $container->get(DatabaseManager::class);
            $container->get(ORM::class);

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
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Get the database manager.
     * 
     * @return DatabaseManager The database manager.
     */
    protected function getDatabaseManager(): DatabaseManager
    {
        return $this->dbm;
    }
}
