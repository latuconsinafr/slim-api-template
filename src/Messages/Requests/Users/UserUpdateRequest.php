<?php

declare(strict_types=1);

namespace App\Messages\Requests\Users;

use App\Data\Entities\UserEntity;
use App\Validators\Users\UserUpdateRequestValidator;

/**
 * User update request data transfer object.
 */
class UserUpdateRequest extends UserCreateRequest
{
    /**
     * @var string The user's id.
     */
    public string $id;

    /**
     * The constructor
     * 
     * @param array $request The incoming request.
     */
    public function __construct(array $request)
    {
        $validated = new UserUpdateRequestValidator($request);

        foreach ($validated->request as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Convert @see UserCreateRequest to @see UserEntity
     * 
     * @return UserEntity
     */
    public function toEntity(): UserEntity
    {
        return new UserEntity($this->userName, $this->email, $this->phoneNumber, $this->password, $this->id);
    }
}
