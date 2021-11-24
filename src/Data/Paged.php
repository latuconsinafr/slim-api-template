<?php

declare(strict_types=1);

namespace App\Data;

/**
 * The generic paged.
 */
class Paged
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

    /**
     * @var iterable The entity results.
     */
    public iterable $results = [];

    /**
     * The constructor.
     * 
     * @param int $limit The page limit.
     * @param int $pageNumber The current page number.
     * @param int $count The total data.
     * @param iterable $results The entity results.
     */
    public function __construct(int $limit, int $pageNumber, int $count, iterable $results)
    {
        $this->pageNumber = $pageNumber;
        $this->limit = $limit;
        $this->totalCount = $count;
        $this->totalPages = (int)ceil($this->totalCount / $this->limit);
        $this->hasPreviousPage =  $pageNumber <= 1 || $pageNumber > $this->totalPages ? false : true;
        $this->hasNextPage = $pageNumber >= $this->totalPages ? false : true;

        // TODO: Fixed this count and results after the pagination exceeding the total pages
        if ($this->pageNumber <= $this->totalPages) {
            $this->count = iterator_count(new \ArrayIterator($results));
            $this->results = $results;
        }
    }
}
