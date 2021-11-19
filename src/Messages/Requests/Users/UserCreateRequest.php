<?php

declare(strict_types=1);

namespace App\Messages\Requests\Users;

use App\Data\Entities\UserEntity;

class UserCreateRequest
{
    public string $userName;
    public ?string $email;
    public ?string $phoneNumber;
    public string $password;

    public function toEntity(): UserEntity
    {
        return new UserEntity($this->userName, $this->email, $this->phoneNumber, $this->password);
    }
}
