<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;
use App\Data\Views\PagedView;

interface UserRepositoryInterface
{
    /**
     * The get all users repository
     * 
     * @return iterable The iterable of @see User
     */
    public function getAll(): iterable;

    /**
     * The get all users with pagination repository
     * 
     * @param int $limit The page limit
     * @param int $pageNumber The current page number
     * 
     * @return PagedView
     */
    public function getAllWithPagination(int $limit, int $pageNumber): PagedView;

    /**
     * The get specified user by id repository
     * 
     * @param int $id The specified user's id
     * 
     * @return User The specified user
     */
    public function get(int $id): ?User;
}
