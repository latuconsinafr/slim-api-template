<?php

namespace App\Messages\Requests\Users;

use App\Data\Entities\User;

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
        parent::__construct($request);
    }

    /**
     * Transform incoming request into associated entity.
     * 
     * @return User The user entity.
     */
    public function toEntity(): User
    {
        return new User(
            $this->request[$this->userName],
            $this->request[$this->email],
            $this->request[$this->phoneNumber],
            $this->request[$this->password],
            $this->request[$this->id]
        );
    }
}
