<?php

namespace App\Messages\Responses\Users;

use App\Data\Entities\User;

/**
 * Responder for single user data
 */
class UserDetailResponse
{
    /**
     * @var string The user's id
     */
    public string $id;

    /**
     * @var string The user's username
     */
    public string $userName;

    /**
     * @var string|null The user's email address
     */
    public ?string $email;

    /**
     * @var string|null The user's phone number
     */
    public ?string $phoneNumber;

    /**
     * The constructor
     * 
     * @param User|null $user The user entity
     */
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
