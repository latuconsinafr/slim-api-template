<?php

declare(strict_types=1);

namespace App\Messages\Requests\Users;

use App\Data\Entities\UserEntity;

/**
 * Define the request key for user update request.
 */
class UserUpdateRequest extends UserCreateRequest
{
    /**
     * @var string The id request key.
     */
    public string $id = 'id';

    /**
     * The constructor.
     * 
     * @param array $request The incoming request.
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }

    /**
     * Transform incoming request into associated entity.
     * 
     * @return UserEntity The user entity.
     */
    public function toEntity(): UserEntity
    {
        return new UserEntity(
            $this->request[$this->userName],
            $this->request[$this->email],
            $this->request[$this->phoneNumber],
            $this->request[$this->password],
            $this->request[$this->id]
        );
    }
}
