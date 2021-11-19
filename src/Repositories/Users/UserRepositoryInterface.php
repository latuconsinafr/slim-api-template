<?php

declare(strict_types=1);

namespace App\Repositories\Users;

use App\Data\Entities\UserEntity;
use App\Repositories\BaseRepositoryInterface;

/**
 * The user repository interface.
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * The get all users with pagination repository.
     * 
     * @return iterable The iterable of @see UserEntity, if any.
     */
    public function findAll(): iterable;

    /**
     * The get specified user by specified key value.
     * 
     * @param array $keyValue The key value pair to find.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findOne(array $keyValue): ?UserEntity;

    /**
     * The get specified user by id repository.
     * 
     * @param string $id The specified user's id to find.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findById(string $id): ?UserEntity;

    /**
     * The create user repository.
     * 
     * @param UserEntity $user The user entity to create.
     * 
     * @return void
     */
    public function create(UserEntity $user): void;

    /**
     * The update user repository.
     * 
     * @param UserEntity $user The user entity to update.
     * 
     * @return void
     */
    public function update(UserEntity $user): void;

    /**
     * The delete user repository.
     * 
     * @param UserEntity $user The user to delete.
     * 
     * @return void
     */
    public function delete(UserEntity $user): void;
}
