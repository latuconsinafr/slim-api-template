<?php

declare(strict_types=1);

namespace App\Messages\Responses\Users;

use App\Data\Entities\UserEntity;
use Ramsey\Uuid\UuidInterface;

/**
 * Responder for single user data.
 */
class UserDetailResponse
{
    /**
     * @var UuidInterface The user's id.
     */
    public UuidInterface $id;

    /**
     * @var string The user's user name.
     */
    public string $userName;

    /**
     * @var string|null The user's email address.
     */
    public ?string $email;

    /**
     * @var string|null The user's phone number.
     */
    public ?string $phoneNumber;

    /**
     * @var \DateTimeImmutable The user's created at.
     */
    public \DateTimeImmutable $createdAt;

    /**
     * @var \DateTimeImmutable The user's updated at.
     */
    public \DateTimeImmutable $updatedAt;

    /**
     * The constructor.
     * 
     * @param User|null $user The user entity.
     */
    public function __construct(?UserEntity $user)
    {
        if ($user instanceof UserEntity) {
            $this->id = $user->id;
            $this->userName = $user->userName;
            $this->email = $user->email;
            $this->phoneNumber = $user->phoneNumber;
            $this->createdAt = $user->createdAt;
            $this->updatedAt = $user->updatedAt;
        }
    }
}
