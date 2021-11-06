<?php

namespace App\Services;

use App\Data\Entities\User;
use App\Repositories\Users\UserRepositoryInterface;

class UserService
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * The constructor
     * 
     * @param UserRepositoryInterface $userRepository The user repository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * The get all users service
     * 
     * @return array The array of @see User
     */
    public function getAll(): array
    {
        return $this->userRepository->getAll();
    }

    /**
     * The get specified user by id service
     * 
     * @param string $id The specified user's id
     * 
     * @return User The specified user
     */
    public function get(string $id): User
    {
        return $this->userRepository->get($id);
    }
}
