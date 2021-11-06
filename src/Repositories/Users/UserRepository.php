<?php

namespace App\Repositories\Users;

use App\Data\Entities\User;
use App\Data\Views\PagedView;
use Cycle\ORM\ORM;
use Cycle\ORM\RepositoryInterface;
use Spiral\Pagination\Paginator;

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
    public function getAll(): iterable
    {
        return $this->repository->findAll();
    }

    /** @inheritdoc */
    public function getAllWithPagination(int $limit, int $pageNumber): PagedView
    {
        $select = $this->repository->select();
        $paginator = new Paginator($limit);
        $paginator->withPage($pageNumber)->paginate($select)->countPages();
        
        return new PagedView(
            $select->fetchAll(),
            $limit,
            $pageNumber,
        );
    }

    /** @inheritdoc */
    public function get(int $id): ?User
    {
        return $this->repository->findByPK($id);
    }
}
