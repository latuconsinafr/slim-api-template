<?php

namespace App\Messages\Requests;

use App\Data\Entities\User;

class UserCreateRequest
{
    public string $userName;
    public ?string $email;
    public ?string $phoneNumber;
    public string $password;

    public function __construct(array $request)
    {
        $request = (object)$request;

        $this->userName = $request->userName;
        $this->email = $request->email ?? null;
        $this->phoneNumber = $request->phoneNumber ?? null;
        $this->password = $request->password;
    }

    public function toEntity(): User
    {
        return new User(
            $this->userName,
            $this->email,
            $this->phoneNumber,
            $this->password
        );
    }
}
