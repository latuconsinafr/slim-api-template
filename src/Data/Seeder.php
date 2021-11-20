<?php

declare(strict_types=1);

include __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../../config/settings.php';

use Spiral\Database;

$initialData = __DIR__ . '/../../resources/setup/initialdata.json';

if (file_exists($initialData)) {
    $initialData = json_decode(file_get_contents($initialData));
    $dbal = new Database\DatabaseManager(new Database\Config\DatabaseConfig($settings['database']));

    try {
        foreach ($initialData as $table => $data) {
            $insert = $dbal->database('default')->insert((string)$table);
            foreach ($data as $value) {
                $value->created_at = new \DateTimeImmutable();
                $value->updated_at = new \DateTimeImmutable();

                $insert->values((array)$value);
            }
            $insert->run();
        }
    } catch (\Throwable $th) {
        print_r($th->getMessage());
    }
}
