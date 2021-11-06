<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;

/**
 * The user repository interface
 */
interface UserRepositoryInterface
{
    /**
     * The get all users with pagination repository
     * 
     * @param int|null $limit The page limit for pagination
     * @param int|null $pageNumber The current page number for pagination
     * 
     * @return iterable The iterable of @see User
     */
    public function getAll(?int $limit = null, ?int $pageNumber = null): iterable;

    /**
     * The get specified user by id repository
     * 
     * @param int $id The specified user's id
     * 
     * @return User The specified user
     */
    public function get(int $id): ?User;
}
