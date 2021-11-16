<?php

declare(strict_types=1);

namespace App\Messages\Requests\Users;

use App\Data\Entities\UserEntity;
use App\Messages\Requests\BaseRequest;

/**
 * Define the request key for user create request.
 */
class UserCreateRequest extends BaseRequest
{
    /**
     * @var string The user name request key.
     */
    public string $userName = 'userName';

    /**
     * @var string The email request key.
     */
    public string $email = 'email';

    /**
     * @var string The phone number request key.
     */
    public string $phoneNumber = 'phoneNumber';

    /**
     * @var string The password request key.
     */
    public string $password = 'password';

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
     * @return UserEntity The User entity.
     */
    public function toEntity(): UserEntity
    {
        return new UserEntity(
            $this->request[$this->userName],
            $this->request[$this->email],
            $this->request[$this->phoneNumber],
            $this->request[$this->password],
        );
    }
}
