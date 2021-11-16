<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Entities\UserEntity;
use App\Data\Paged;
use App\Repositories\Users\UserRepositoryInterface;
use InvalidArgumentException;

/**
 * The user service.
 */
class UserService
{
    /**
     * @var UserRepositoryInterface The user repository interface.
     */
    private UserRepositoryInterface $userRepository;

    /**
     * The constructor.
     * 
     * @param UserRepositoryInterface $userRepository The user repository.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * The find all service.
     * 
     * @return iterable The iterable of @see UserEntity.
     */
    public function findAll(): iterable
    {
        // Algorithm
        return $this->userRepository->findAll();
    }

    /**
     * The find all with query parameters service.
     * 
     * @param int $limit The page limit.
     * @param int $pageNumber The current page number.
     * @param string $orderByKey The order by key.
     * @param string $orderByMethod The order by method.
     * @param string $search The value to search.
     * 
     * @return Paged The iterable of @see UserEntity which contains a specified search value with @see Paged object, if any.
     */
    public function findAllWithQuery(int $limit = 5, int $pageNumber = 1, string $orderByKey = 'id', string $orderByMethod = 'asc', string $search = ''): Paged
    {
        // Algorithm
        $results = $this->userRepository
            ->search($search)
            ->orderBy($orderByKey, $orderByMethod)
            ->paginate($limit, $pageNumber)
            ->fetchAll();
        $count = $this->userRepository->count();

        return new Paged($limit, $pageNumber, $count, $results);
    }

    /**
     * The find by id service.
     * 
     * @param string $id The specified user's id to find.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findById(string $id): ?UserEntity
    {
        // Algorithm
        if (!is_string($id)) {
            throw new InvalidArgumentException("The type of given id is not a string. Input was: {$id}");
        }
        if (is_null($id)) {
            throw new InvalidArgumentException("The given id value is null");
        }

        return $this->userRepository->findById($id);
    }

    /**
     * The create user service.
     * 
     * @param UserEntity $user The user entity to create.
     * 
     * @return void
     */
    public function create(UserEntity $user): void
    {
        // Algorithm
        if (!$user instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
        }

        $this->userRepository->create($user);
    }

    /**
     * The update user service.
     * 
     * @param UserEntity $user The user entity to update.
     * 
     * @return void
     */
    public function update(UserEntity $user): void
    {
        // Algorithm
        if (!$user instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
        }

        $this->userRepository->update($user);
    }

    /**
     * The delete user service.
     * 
     * @param string $id The specified user's id to delete.
     * 
     * @return void
     */
    public function delete(string $id): void
    {
        // Algorithm
        if (!is_string($id)) {
            throw new InvalidArgumentException("The type of given id is not a string. Input was: {$id}");
        }
        if (is_null($id)) {
            throw new InvalidArgumentException("The given id value is null");
        }

        $user = $this->findById($id);

        if (!$user instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
        }

        $this->userRepository->delete($user);
    }
}
