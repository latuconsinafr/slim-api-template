<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Entities\UserEntity;
use App\Data\Paged;
use App\Repositories\Users\UserRepositoryInterface;
use App\Supports\Loggers\Logger;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface The logger interface.
     */
    private LoggerInterface $logger;

    /**
     * The constructor.
     * 
     * @param UserRepositoryInterface $userRepository The user repository.
     * @param Logger $logger The generic logger.
     */
    public function __construct(UserRepositoryInterface $userRepository, Logger $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger->addFileHandler()
            ->addConsoleHandler()
            ->createLogger();
    }

    /**
     * The find all service.
     * 
     * @return iterable The iterable of @see UserEntity.
     */
    public function findAll(): iterable
    {
        // Algorithm
        $this->logger->info("Calling UserService findAll method.");

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
        $this->logger->info("Calling UserService findAllWithQuery method.");

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
        $this->logger->info("Calling UserService findById method with id {$id}.");

        return $this->userRepository->findById($id);
    }

    /**
     * The find by user name service.
     * 
     * @param string $userName The specified user's user name to find.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findByUserName(string $userName): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService findByUserName method with user name {$userName}.");

        return $this->userRepository->findOne(['userName' => $userName]);
    }

    /**
     * The find by email service.
     * 
     * @param string $email The specified user's email to find.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findByEmail(string $email): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService findByEmail method with email {$email}.");

        return $this->userRepository->findOne(['email' => $email]);
    }

    /**
     * The find by phone number service.
     * 
     * @param string $phoneNumber The specified user's phone number to find.
     * 
     * @return UserEntity|null The user entity, if any.
     */
    public function findByPhoneNumber(string $phoneNumber): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService findByPhoneNumber method with phone number {$phoneNumber}.");

        return $this->userRepository->findOne(['phoneNumber' => $phoneNumber]);
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
        $this->logger->info("Calling UserService create method with username {$user->getUserName()}.");

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
        $this->logger->info("Calling UserService update method with id {$user->getId()}.");

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
        $this->logger->info("Calling UserService delete method with id {$id}.");

        $user = $this->findById($id);

        if (!$user instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
        }

        $this->userRepository->delete($user);
    }
}
