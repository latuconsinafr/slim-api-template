<?php

declare(strict_types=1);

namespace App\Messages\Requests\Users;

use App\Data\Entities\UserEntity;
use App\Data\TypeCasts\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * User update request data transfer object.
 */
class UserUpdateRequest extends UserCreateRequest
{
    /**
     * @var UuidInterface The user's id.
     */
    public UuidInterface $id;

    /**
     * The constructor
     * 
     * @param array $request The incoming request.
     */
    public function __construct(array $request)
    {
        foreach ($request as $key => $value) {
            $this->$key = $key === 'id' ? Uuid::fromString($value) : $value;
        }
    }

    /**
     * Convert @see UserCreateRequest to @see UserEntity
     * 
     * @return UserEntity
     */
    public function toEntity(): UserEntity
    {
        $user = new UserEntity();

        $user->id = $this->id;
        $user->userName = $this->userName;
        $user->email = $this->email;
        $user->phoneNumber = $this->phoneNumber;
        $user->password = $this->password;

        return $user;
    }
}
