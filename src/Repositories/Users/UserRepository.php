<?php

declare(strict_types=1);

namespace App\Repositories\Users;

use App\Data\Entities\UserEntity;
use App\Repositories\BaseRepository;
use App\Supports\Loggers\Logger;
use Cycle\ORM\ORM;
use Cycle\ORM\Transaction;
use Ramsey\Uuid\UuidInterface;

/**
 * The user repository.
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * The constructor.
     * 
     * @param ORM $orm The cycle orm class.
     * @param Logger $logger The generic logger.
     */
    public function __construct(ORM $orm, Logger $logger)
    {
        parent::__construct($logger);

        $this->transaction = new Transaction($orm);
        $this->repository = $orm->getRepository(UserEntity::class);

        $this->fields = array_merge(['id', 'user_name', 'email', 'phone_number'], $this->fields);
    }

    /** 
     * @inheritdoc
     */
    public function findAll(): iterable
    {
        // Algorithm
        $this->logger->info("Calling UserRepository findAll method.");

        return $this->repository->findAll();
    }

    /**
     * @inheritdoc
     */
    public function findOne(array $keyValue): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserRepository findOne method with key value: " . json_encode($keyValue));

        return $this->repository->findOne($keyValue);
    }

    /**
     * @inheritdoc
     */
    public function findById(UuidInterface $id): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserRepository findById method with id {$id}.");

        return $this->repository->findByPK($id);
    }

    /**
     * @inheritdoc
     */
    public function create(UserEntity $user): void
    {
        // Algorithm
        $this->logger->info("Calling UserRepository create method with username {$user->userName}.");

        if (!$user instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($user));
        }

        $this->transaction->persist($user);
        $this->run();
    }

    /**
     * @inheritdoc
     */
    public function update(UserEntity $user): void
    {
        // Algorithm
        $this->logger->info("Calling UserRepository update method with id {$user->id}.");

        if (!$user instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($user));
        }

        $userToUpdate = $this->findById($user->id);

        if (!$userToUpdate instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($userToUpdate));
        }

        $userToUpdate->userName = $user->userName;
        $userToUpdate->email = $user->email;
        $userToUpdate->phoneNumber = $user->phoneNumber;
        $userToUpdate->password = $user->password;

        $this->transaction->persist($userToUpdate);
        $this->run();
    }

    /** 
     * @inheritdoc
     */
    public function delete(UserEntity $user): void
    {
        // Algorithm
        $this->logger->info("Calling UserRepository delete method with id {$user->id}.");

        if (!$user instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($user));
        }

        $this->transaction->delete($user);
        $this->run();
    }
}
