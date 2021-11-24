<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Data\Entities\UserEntity;
use App\Data\TypeCasts\Uuid;

/**
 * The user test context.
 */
class UserFixture
{
    /**
     * @var string The table name test context.
     */
    public string $table = 'users';

    /**
     * @var string The user 1 id sample.
     */
    public string $user1Id = "3ad17720-f8b3-49ab-ab18-a17c3b02c039";

    /**
     * @var string The user 1 user name sample.
     */
    public string $user1UserName = "user1";

    /**
     * @var string The user 1 email sample.
     */
    public string $user1Email = "user1@gmail.com";

    /**
     * @var string The user 1 phone number sample.
     */
    public string $user1PhoneNumber = "+6282246924990";

    /**
     * Get the user sample records.
     * 
     * @return iterable The iterable of @see UserEntity.
     */
    public function getUsers(): iterable
    {
        // Get the initial data
        $initialData = __DIR__ . '/../../resources/setup/initialdata.json';

        if (!file_exists($initialData)) {
        }

        $initialData = json_decode(file_get_contents($initialData), true);

        foreach ($initialData[$this->table] as $value) {
            $user = new UserEntity();

            $user->id = Uuid::fromString($value['id']);
            $user->userName = $value['user_name'];
            $user->email = $value['email'];
            $user->phoneNumber = $value['phone_number'];
            $user->password = $value['password'];

            $users[] = $user;
        }

        return $users;
    }
}
