<?php

namespace App\Messages\Responses\Users;

use App\Data\Entities\User;

class UserDetailResponse
{
    public int $id;
    public string $userName;
    public ?string $email;
    public ?string $phoneNumber;

    public function __construct(?User $user)
    {
        if ($user instanceof User) {
            $this->id = $user->getId();
            $this->userName = $user->getUserName();
            $this->email = $user->getEmail();
            $this->phoneNumber = $user->getPhoneNumber();
        }
    }
}
