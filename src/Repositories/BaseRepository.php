<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Supports\Loggers\Logger;
use Cycle\ORM\{Select, Transaction};
use Cycle\ORM\Select\Repository;
use Psr\Log\LoggerInterface;
use Spiral\Database\Exception\StatementException\{ConnectionException, ConstrainException};
use Spiral\Pagination\Paginator;

/**
 * The generic repository
 */
class BaseRepository
{
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
     * @var LoggerInterface The logger interface.
     */
    protected LoggerInterface $logger;

    /**
     * @var array The where criteria.
     */
    protected array $criteria = [];

    /**
     * @var array The entity search able fields.
     */
    protected array $searchAbleFields = [];

    /**
     * @var array The entity sort able fields.
     */
    protected array $sortAbleFields = ['created_at', 'updated_at'];

    /**
     * @param Logger $logger The logger.
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger->addFileHandler()
            ->addConsoleHandler()
            ->createLogger();
    }

    /**
     * @inheritdoc
     */
    public function query(array $criteria = []): BaseRepository
    {
        // Algorithm
        $this->logger->info("Calling BaseRepository query method with criteria: " . json_encode($criteria));

        $this->criteria = $criteria;
        $this->select = $this->repository->select();

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function search(string $value = ''): BaseRepository
    {
        // Algorithm
        $this->logger->info("Calling BaseRepository search method with value {$value}.");

        if ($value != '') {
            foreach ($this->searchAbleFields as $field) {
                $this->select = $this->select->orWhere($field, 'like', "%{$value}%");
            }
        }

        if (!empty($this->criteria)) {
            $this->select->where(function (\Cycle\ORM\Select\QueryBuilder $select) {
                $select->andWhere($this->criteria);
            });
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function orderBy(string $key = 'createdAt', $sortMethod = 'asc'): BaseRepository
    {
        // Algorithm
        $this->logger->info("Calling BaseRepository orderBy method with key {$key} and sort method {$sortMethod}.");

        $sortMethod = strtoupper($sortMethod);
        $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));

        if (
            in_array($key, $this->sortAbleFields)
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
        $this->logger->info("Calling BaseRepository paginate method with limit {$limit} and page number {$pageNumber}.");

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
        $this->logger->info("Calling BaseRepository fetchAll method.");

        return $this->select->fetchAll();
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        // Algorithm
        $this->logger->info("Calling BaseRepository count method.");

        return $this->repository->select()->count();
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
            $this->logger->info("Calling BaseRepository run method.");
            $this->transaction->run();
        } catch (ConnectionException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        } catch (ConstrainException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}
