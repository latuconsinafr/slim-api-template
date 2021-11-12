<?php

declare(strict_types=1);

namespace App\Repositories\Users;

use App\Data\Entities\User;
use App\Data\Views\PagedView;
use Cycle\ORM\{ORM, Transaction};
use Cycle\ORM\Select\Repository;
use Spiral\Pagination\Paginator;
use Spiral\Database\Exception\StatementException\{ConnectionException, ConstrainException};

/**
 * The user repository.
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var Transaction The cycle orm transaction.
     */
    private Transaction $transaction;

    /**
     * @var Repository The cycle orm repository interface.
     */
    private Repository $repository;

    /**
     * @var array The search able fields
     */
    private array $searchFields = ['user_name', 'email', 'phone_number'];

    /**
     * @var array The sort able fields
     */
    private array $sortFields = ['id', 'user_name', 'email', 'phone_number'];

    /**
     * The constructor.
     * 
     * @param ORM $orm.
     */
    public function __construct(ORM $orm)
    {
        $this->transaction = new Transaction($orm);
        $this->repository = $orm->getRepository(User::class);
    }

    /** 
     * @inheritdoc
     */
    public function findAll(): iterable
    {
        return $this->repository->findAll();
    }

    /**
     * @inheritdoc
     */
    public function search(array $query): PagedView
    {
        $select = $this->repository->select();

        // Search
        if (isset($query['search']) && strlen($query['search']) > 0) {
            $search = $query['search'];

            foreach ($this->searchFields as $value) {
                $select->orWhere($value, 'like', $search);
            }
        }

        // Order
        if (isset($query['orderBy']) && strlen($query['orderBy']) > 0) {
            $orderBy = explode(":", $query['orderBy']);

            if (isset($orderBy[0]) && isset($orderBy[1])) {
                $attribute = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $orderBy[0]));
                $sortMethod = strtoupper($orderBy[1]);

                if (in_array($attribute, $this->sortFields) && ($sortMethod == 'ASC' || $sortMethod == 'DESC')) {
                    $select->orderBy($orderBy[0], strtoupper($orderBy[1]));
                }
            }
        }

        // Pagination
        $limit = 5;
        $pageNumber = 1;

        if (isset($query['limit']) && $query['limit'] > 0) {
            $limit = (int)$query['limit'];
        }
        if (isset($query['pageNumber']) && $query['pageNumber'] >= 0) {
            $pageNumber = (int)$query['pageNumber'];
        }

        $count = $select->count();

        (new Paginator($limit))->withPage($pageNumber)->paginate($select);

        return new PagedView($pageNumber, $limit, $count, $select->fetchAll());
    }

    /**
     * @inheritdoc
     */
    public function findById(string $id): ?User
    {
        return $this->repository->findByPK($id);
    }

    /**
     * @inheritdoc
     */
    public function add(User $user): void
    {
        $this->transaction->persist($user);
        $this->run();
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
