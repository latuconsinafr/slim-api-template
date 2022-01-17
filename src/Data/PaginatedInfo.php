<?php

declare(strict_types=1);

namespace App\Data;

use App\Messages\Params\PaginatedParam;

/**
 * The generic paginated info.
 */
class PaginatedInfo
{
    /**
     * @var int The page limit.
     */
    public int $limit;

    /**
     * @var int The current page number.
     */
    public int $pageNumber;

    /**
     * @var int The total data in current page.
     */
    public int $count = 0;

    /**
     * @var int The total data.
     */
    public int $totalCount = 0;

    /**
     * @var int The total pages.
     */
    public int $totalPages = 0;

    /**
     * @var bool The flag indicates whether has previous page or not.
     */
    public bool $hasPreviousPage;

    /**
     * @var bool The flag indicates whether has next page or not.
     */
    public bool $hasNextPage;

    public function __construct(PaginatedParam $paginatedParam, int $count, iterable $results)
    {
        $this->pageNumber = $paginatedParam->pageNumber;
        $this->limit = $paginatedParam->limit;
        $this->totalCount = $count;
        $this->totalPages = (int)ceil($count / $paginatedParam->limit);
        $this->hasPreviousPage =  $paginatedParam->pageNumber <= 1 || $paginatedParam->pageNumber > $this->totalPages ? false : true;
        $this->hasNextPage = $paginatedParam->pageNumber >= $this->totalPages ? false : true;

        if ($this->pageNumber <= $this->totalPages) {
            $this->count = iterator_count(new \ArrayIterator($results));
        }
    }
}
