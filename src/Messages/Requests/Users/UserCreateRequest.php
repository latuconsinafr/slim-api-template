<?php

namespace App\Messages\Requests\Users;

use App\Data\Entities\User;
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
        );
    }
}
