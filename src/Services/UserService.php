<?php

namespace App\Services;

use App\Data\Entities\User;
use App\Repositories\Users\UserRepositoryInterface;

/**
 * The user service
 */
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
     * @param int|null $limit The page limit for pagination
     * @param int|null $pageNumber The current page number for pagination
     * 
     * @return iterable The iterable of @see User
     */
    public function getAll(?int $limit = null, ?int $pageNumber = null): iterable
    {
        return $this->userRepository->getAll($limit, $pageNumber);
    }

    /**
     * The get specified user by id service
     * 
     * @param int $id The specified user's id
     * 
     * @return User The specified user
     */
    public function get(int $id): ?User
    {
        return $this->userRepository->get($id);
    }
}
