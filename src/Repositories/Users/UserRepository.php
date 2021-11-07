<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;
use Cycle\ORM\ORM;
use Cycle\ORM\RepositoryInterface;
use Cycle\ORM\Transaction;
use Spiral\Pagination\Paginator;
use Spiral\Database\Exception\StatementException\{ConnectionException, ConstrainException};

/**
 * The user repository
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Transaction The cycle orm transaction
     */
    private Transaction $transaction;

    /**
     * @var RepositoryInterface The cycle orm repository interface
     */
    private RepositoryInterface $repository;

    /**
     * The constructor
     * 
     * @param ORM $orm
     */
    public function __construct(ORM $orm)
    {
        $this->transaction = new Transaction($orm);
        $this->repository = $orm->getRepository(User::class);
    }

    /** 
     * {@inheritdoc} 
     */
    public function findAll(?int $limit = null, ?int $pageNumber = null): iterable
    {
        if (!is_int($limit) || !is_int($pageNumber)) {
            return $this->repository->findAll();
        }

        $select = $this->repository->select();
        $paginator = new Paginator($limit);
        $paginator->withPage($pageNumber)->paginate($select)->countPages();

        return $select->fetchAll();
    }

    /** 
     * {@inheritdoc} 
     */
    public function findById(string $id): ?User
    {
        return $this->repository->findByPK($id);
    }

    /** 
     * {@inheritdoc} 
     */
    public function add(User $user): void
    {
        $this->transaction->persist($user);
        $this->run();
    }

    /** 
     * {@inheritdoc} 
     */
    public function update(User $user): void
    {
        $userToBeUpdated = $this->repository->findByPK($user->getId());

        if ($userToBeUpdated instanceof User) {
            $userToBeUpdated->setUserName($user->getUserName());
            $userToBeUpdated->setEmail($user->getEmail());
            $userToBeUpdated->setPhoneNumber($user->getPhoneNumber());
            $userToBeUpdated->setPassword($user->getPassword());

            $this->transaction->persist($userToBeUpdated);
            $this->run();
        }
    }

    /** 
     * {@inheritdoc} 
     */
    public function delete(string $id): void
    {
        $userToBeDeleted = $this->repository->findByPK($id);

        if ($userToBeDeleted instanceof User) {
            $this->transaction->delete($userToBeDeleted);
            $this->run();
        }
    }

    /**
     * The transaction runner function
     * 
     * @return void
     */
    private function run(): void
    {
        try {
            $this->transaction->run();
        } catch (ConnectionException $e) {
            print_r("Database has gone away.");
        } catch (ConstrainException $e) {
            print_r("Database constrain not met.");
        }
    }
}
