<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $database;

    /**
     * The constructor
     */
    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    /** @inheritdoc */
    public function getAll(): array
    {
        $sth = $this->database->prepare("SELECT * FROM users");
        $sth->execute();

        if ($sth->rowCount() > 0) {
            return $sth->fetchAll(PDO::FETCH_CLASS, User::class);
        } else {
            return array();
        }
    }

    /** @inheritdoc */
    public function get(string $id): User
    {
        $sth = $this->database->prepare("SELECT * FROM users WHERE id = :id");
        $sth->execute([':id' => $id]);

        if ($sth->rowCount() > 0) {
            $data = $sth->fetch(PDO::FETCH_OBJ);

            $user = new User();
            $user->setEntity($data->id, $data->user_name, $data->email, $data->phone_number, $data->password);

            return $user;
        } else {
            return new User();
        }
    }
}
