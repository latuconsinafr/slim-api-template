<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;
use Cycle\ORM\ORM;
use Cycle\ORM\RepositoryInterface;
use Spiral\Pagination\Paginator;

/**
 * The user repository
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var ORM
     */
    private RepositoryInterface $repository;

    /**
     * The constructor
     */
    public function __construct(ORM $orm)
    {
        $this->repository = $orm->getRepository(User::class);
    }

    /** @inheritdoc */
    public function getAll(?int $limit = null, ?int $pageNumber = null): iterable
    {
        if (!is_int($limit) || !is_int($pageNumber)) {
            return $this->repository->findAll();
        }

        $select = $this->repository->select();
        $paginator = new Paginator($limit);
        $paginator->withPage($pageNumber)->paginate($select)->countPages();

        return $select->fetchAll();
    }

    /** @inheritdoc */
    public function get(int $id): ?User
    {
        return $this->repository->findByPK($id);
    }
}
