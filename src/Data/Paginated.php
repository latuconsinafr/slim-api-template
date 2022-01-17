<?php

declare(strict_types=1);

namespace App\Data;

/**
 * The generic paginated.
 */
class Paginated
{
    /**
     * @var PaginatedInfo The page info for pagination.
     */
    public PaginatedInfo $paginatedInfo;

    /**
     * @var iterable The iterable of results.
     */
    public iterable $results = [];

    /**
     * The constructor.
     * 
     * @param PaginatedInfo $paginatedInfo The paginated info.
     * @param iterable $results The iterable of entity.
     */
    public function __construct(PaginatedInfo $paginatedInfo, iterable $results)
    {
        $this->paginatedInfo = $paginatedInfo;
        $this->results = $results;
    }
}
