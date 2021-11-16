<?php

declare(strict_types=1);

namespace App\Repositories\Users;

use App\Data\Entities\UserEntity;
use App\Repositories\BaseRepository;
use Cycle\ORM\ORM;
use Cycle\ORM\Transaction;
use InvalidArgumentException;

/**
 * The user repository.
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * The constructor.
     */
    public function __construct(ORM $orm)
    {
        $this->orm = $orm;
        $this->transaction = new Transaction($this->orm);
        $this->repository = $orm->getRepository(UserEntity::class);

        $this->fields = ['user_name', 'email', 'phone_number'];
    }

    /** 
     * @inheritdoc
     */
    public function findAll(): iterable
    {
        // Algorithm
        return $this->repository->findAll();
    }

    /**
     * @inheritdoc
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

        return $this->repository->findByPK($id);
    }

    /**
     * @inheritdoc
     */
    public function create(UserEntity $user): void
    {
        // Algorithm
        if (!$user instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
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
        if (!$user instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
        }

        $userToUpdate = $this->findById($user->getId());

        if (!$userToUpdate instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
        }

        $userToUpdate->setUserName($user->getUserName());
        $userToUpdate->setEmail($user->getEmail());
        $userToUpdate->setPhoneNumber($user->getPhoneNumber());
        $userToUpdate->setPassword($user->getPassword());

        $this->transaction->persist($userToUpdate);
        $this->run();
    }

    /** 
     * @inheritdoc
     */
    public function delete(UserEntity $user): void
    {
        // Algorithm
        if (!$user instanceof UserEntity) {
            throw new InvalidArgumentException("User is not an instance of UserEntity. Input was: {$user}");
        }

        $this->transaction->delete($user);
        $this->run();
    }
}
