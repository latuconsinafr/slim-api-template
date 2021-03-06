<?php

declare(strict_types=1);

namespace App\Messages\Requests\Users;

use App\Data\Entities\UserEntity;
use App\Data\TypeCasts\Uuid;

/**
 * User create request data transfer object.
 */
class UserCreateRequest
{
    /**
     * @var string The user's user name.
     */
    public string $userName;

    /**
     * @var string|null The user's email address.
     */
    public ?string $email;

    /**
     * @var string|null The user's phone number.
     */
    public ?string $phoneNumber;

    /**
     * @var string The user's password.
     */
    public string $password;

    /**
     * The constructor
     * 
     * @param array $request The incoming request.
     */
    public function __construct(array $request)
    {
        foreach ($request as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Convert @see UserCreateRequest to @see UserEntity.
     * 
     * @return UserEntity The user entity.
     */
    public function toEntity(): UserEntity
    {
        $user = new UserEntity();

        $user->id = Uuid::create();
        $user->userName = $this->userName;
        $user->email = $this->email;
        $user->phoneNumber = $this->phoneNumber;
        $user->password = $this->password;

        return $user;
    }
}
