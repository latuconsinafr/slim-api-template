<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;

interface UserRepositoryInterface
{
    /**
     * The get all users repository
     * 
     * @return array The array of @see User
     */
    public function getAll(): array;

    /**
     * The get specified user by id repository
     * 
     * @param string $id The specified user's id
     * 
     * @return User The specified user
     */
    public function get(string $id): User;
}
