<?php

namespace App\Services;

use App\Data\Entities\User;
use App\Messages\Requests\UserCreateRequest;
use App\Messages\Requests\UserUpdateRequest;
use App\Repositories\Users\UserRepositoryInterface;

/**
 * The user service
 */
class UserService
{
    /**
     * @var UserRepositoryInterface The user repository interface
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
    public function getUsers(?int $limit = null, ?int $pageNumber = null): iterable
    {
        return $this->userRepository->findAll($limit, $pageNumber);
    }

    /**
     * The get specified user by id service
     * 
     * @param string $id The specified user's id
     * 
     * @return User The specified user
     */
    public function getUserById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * The create user service
     * 
     * @param UserCreateRequest $request The create request
     * 
     * @return void
     */
    public function createUser(UserCreateRequest $request): void
    {
        $this->userRepository->add($request->toEntity());
    }

    /**
     * The update specified user by id service
     * 
     * @param UserUpdateRequest $request The update request
     * 
     * @return void
     */
    public function updateUser(UserUpdateRequest $request): void
    {
        $this->userRepository->update($request->toEntity());
    }

    /**
     * The delete specified user by id service
     * 
     * @param string $id The specified user's id
     * 
     * @return void
     */
    public function deleteUser(string $id): void
    {
        $this->userRepository->delete($id);
    }
}
