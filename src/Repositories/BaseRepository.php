<?php

declare(strict_types=1);

namespace App\Repositories;

use Cycle\ORM\{ORM, Select, Transaction};
use Cycle\ORM\Select\Repository;
use Spiral\Database\Exception\StatementException\{ConnectionException, ConstrainException};
use Spiral\Pagination\Paginator;

/**
 * The generic repository
 */
class BaseRepository
{
    /**
     * @var ORM The cycle orm.
     */
    protected ORM $orm;

    /**
     * @var Transaction The cycle orm transaction.
     */
    protected Transaction $transaction;

    /**
     * @var Repository The cycle orm repository.
     */
    protected Repository $repository;

    /**
     * @var Select The cycle orm select.
     */
    protected Select $select;

    /**
     * @var Paginator The spiral paginator.
     */
    protected Paginator $paginator;

    /**
     * @var array The entity fields
     */
    protected array $fields = [];

    /**
     * The constructor.
     */
    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function search(string $value = ''): BaseRepository
    {
        // Algorithm
        $this->select = $this->repository->select();

        foreach ($this->fields as $field) {
            $this->select = $this->select->orWhere($field, 'like', "%{$value}%");
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        // Algorithm
        return $this->repository->select()->count();
    }

    /**
     * @inheritdoc
     */
    public function orderBy(string $key = 'id', $sortMethod = 'asc'): BaseRepository
    {
        // Algorithm
        $sortMethod = strtoupper($sortMethod);
        $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));

        if (
            in_array($key, array_merge(['id', $this->fields]))
            && ($sortMethod == 'ASC'
                || $sortMethod == 'DESC')
        ) {
            $this->select = $this->select->orderBy($key, $sortMethod);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $limit = 5, int $pageNumber = 1): BaseRepository
    {
        // Algorithm
        $paginator = new Paginator($limit);

        $paginator->withPage($pageNumber)->paginate($this->select);
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function fetchAll(): iterable
    {
        // Algorithm
        return $this->select->fetchAll();
    }

    /**
     * The transaction runner function
     * 
     * @return void
     */
    protected function run(): void
    {
        // Algorithm
        try {
            $this->transaction->run();
        } catch (ConnectionException $e) {
            throw $e;
        } catch (ConstrainException $e) {
            throw $e;
        }
    }
}
