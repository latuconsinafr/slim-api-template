<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\Entities\UserEntity;
use App\Data\Paginated;
use App\Data\PaginatedInfo;
use App\Messages\Params\PaginatedParam;
use App\Supports\Loggers\Logger;
use Cycle\ORM\ORM;
use Cycle\ORM\Select\Repository;
use Cycle\ORM\Transaction;
use Ramsey\Uuid\Rfc4122\UuidInterface;
use Spiral\Pagination\Paginator;

class UserService
{
    /**
     * @var Transaction The generic cycle orm transaction.
     */
    private Transaction $transaction;

    /**
     * @var Repository The user repository.
     */
    private Repository $userRepository;

    public function __construct(ORM $orm, Logger $logger)
    {
        $this->transaction = new Transaction($orm);
        $this->userRepository = $orm->getRepository(UserEntity::class);
        $this->logger = $logger->addFileHandler()
            ->addConsoleHandler()
            ->createLogger();

        $this->searchAbleFields = ['id', 'user_name', 'email', 'phone_number'];
        $this->sortAbleFields = ['id', 'user_name', 'email', 'phone_number', 'created_at', 'updated_at'];
    }

    public function findAll(): iterable
    {
        // Algorithm
        $this->logger->info("Calling UserService findAll method.");

        return $this->userRepository->findAll();
    }

    public function findAllWithQuery(PaginatedParam $paginatedParam): Paginated
    {
        // Algorithm
        $this->logger->info("Calling UserService findAllWithQuery method.");

        $select = $this->userRepository->select();

        // Search
        if ($paginatedParam->search != '') {
            foreach ($this->searchAbleFields as $field) {
                $select = $select->orWhere($field, 'like', "%{$paginatedParam->search}%");
            }
        }

        // Order By
        $orderByKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $paginatedParam->orderByKey));
        if (
            in_array($orderByKey, $this->sortAbleFields)
            && ($paginatedParam->orderByMethod == 'ASC'
                || $paginatedParam->orderByMethod == 'DESC')
        ) {
            $this->select = $this->select->orderBy($orderByKey, $paginatedParam->orderByMethod);
        }

        // Paginate
        $paginator = new Paginator($paginatedParam->limit);
        $paginator->withPage(($paginatedParam->pageNumber))->paginate($select);

        // Results & Count
        $results = $select->fetchAll();
        $count = $this->userRepository->select()->count();

        $pageInfo = new PaginatedInfo($paginatedParam, $count, $results);

        return new Paginated($pageInfo, $results);
    }

    public function findById(UuidInterface $id): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService findById method with id {$id}.");

        return $this->userRepository->findByPK($id);
    }

    public function findByUserName(string $userName): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService findByUserName method with user name {$userName}.");

        return $this->userRepository->findOne(['userName' => $userName]);
    }

    public function findByEmail(string $email): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService findByEmail method with email {$email}.");

        return $this->userRepository->findOne(['email' => $email]);
    }

    public function findByPhoneNumber(string $phoneNumber): ?UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService findByPhoneNumber method with phone number {$phoneNumber}.");

        return $this->userRepository->findOne(['phoneNumber' => $phoneNumber]);
    }

    public function create(UserEntity $user): UserEntity
    {
        // Algorithm
        $this->logger->info("Calling UserService create method with username {$user->userName}.");

        if (!$user instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($user));
        }

        $this->transaction->persist($user);
        $this->transaction->run();

        return $user;
    }

    public function update(UserEntity $user): bool
    {
        // Algorithm
        $this->logger->info("Calling UserService update method with id {$user->id}.");

        $userToUpdate = $this->findById($user->id);

        if (!$user instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($user));
        }

        if (!$userToUpdate instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($userToUpdate));
        }

        $userToUpdate->userName = $user->userName;
        $userToUpdate->email = $user->email;
        $userToUpdate->phoneNumber = $user->phoneNumber;
        $userToUpdate->password = $user->password;

        $this->transaction->persist($userToUpdate);
        $this->transaction->run();

        return true;
    }

    public function delete(UuidInterface $id): bool
    {
        // Algorithm
        $this->logger->info("Calling UserService delete method with id {$id}.");

        $user = $this->findById($id);

        if (!$user instanceof UserEntity) {
            throw new \InvalidArgumentException("User is not an instance of UserEntity. Input was: " . json_encode($user));
        }

        $this->transaction->delete($user);
        $this->transaction->run();

        return true;
    }
}
