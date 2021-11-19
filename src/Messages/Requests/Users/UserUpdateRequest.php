<?php

declare(strict_types=1);

namespace App\Messages\Requests\Users;

use App\Data\Entities\UserEntity;

class UserUpdateRequest extends UserCreateRequest
{
    public string $id;

    public function toEntity(): UserEntity
    {
        return new UserEntity($this->userName, $this->email, $this->phoneNumber, $this->password);
    }
}
