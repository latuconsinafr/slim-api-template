<?php

namespace App\Services;

use App\Data\Entities\User;
use App\Data\Views\PagedView;
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
     * @return iterable The iterable of @see User
     */
    public function getAll(): iterable
    {
        return $this->userRepository->getAll();
    }

    /**
     * The get all users service with pagination
     * 
     * @return PagedView The @see PagedView of iterable @see User
     */
    public function getAllWithPagination(int $limit, int $pageNumber): PagedView
    {
        return $this->userRepository->getAllWithPagination($limit, $pageNumber);
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
