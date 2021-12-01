<?php

declare(strict_types=1);

namespace App\Repositories\Users;

use App\Data\Entities\UserEntity;
use App\Repositories\BaseRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * The user repository interface.
 */
interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * The get all users repository.
     * 
     * @return iterable The iterable of @see UserEntity, if any.
     */
    public function findAll(): iterable;

    /**
     * The get all users with criteria repository.
     * 
     * @param array $criteria The criteria to apply.
     * 
     * @return iterable The iterable of @see UserEntity, if any.
     */
    public function find(array $criteria): iterable;

    /**
     * The get specified user by specified criteria.
     * 
     * @param array $criteria The criteria to apply.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findOne(array $criteria): ?UserEntity;

    /**
     * The get specified user by id repository.
     * 
     * @param string $id The specified user's id to find.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findById(UuidInterface $id): ?UserEntity;

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
