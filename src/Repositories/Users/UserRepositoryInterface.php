<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;

/**
 * The user repository interface.
 */
interface UserRepositoryInterface
{
    /**
     * The get all users with pagination repository.
     * 
     * @param int|null $limit The page limit for pagination.
     * @param int|null $pageNumber The current page number for pagination.
     * 
     * @return iterable The iterable of @see User.
     */
    public function findAll(?int $limit = null, ?int $pageNumber = null): iterable;

    /**
     * The get specified user by id repository.
     * 
     * @param string $id The specified user's id.
     * 
     * @return User The specified user.
     */
    public function findById(string $id): ?User;

    /**
     * The create user repository.
     * 
     * @param User $user The user entity to be created.
     * 
     * @return void
     */
    public function add(User $user): void;

    /**
     * The update user repository.
     * 
     * @param User $user The user entity to be updated.
     * 
     * @return void
     */
    public function update(User $user): void;

    /**
     * The delete specified user by id repository.
     * 
     * @param string $id The specified user's id.
     * 
     * @return void
     */
    public function delete(string $id): void;
}
