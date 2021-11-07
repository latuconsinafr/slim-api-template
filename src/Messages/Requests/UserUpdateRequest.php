<?php

namespace App\Messages\Requests;

use App\Data\Entities\User;

class UserUpdateRequest extends UserCreateRequest
{
    public string $id;

    public function __construct(array $request)
    {
        $request = (object)$request;

        $this->id = $request->id;
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
            $this->password,
            $this->id
        );
    }
}
