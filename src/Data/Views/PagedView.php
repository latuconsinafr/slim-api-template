<?php

declare(strict_types=1);

namespace App\Data\Views;

use ArrayIterator;

/**
 * The paged view
 */
class PagedView
{
    /**
     * @var int The current page number
     */
    public int $pageNumber;

    /**
     * @var int The page limit
     */
    public int $limit;

    /**
     * @var int The total data in current page
     */
    public int $count;

    /**
     * @var int The total data
     */
    public int $totalCount;

    /**
     * @var int The total pages
     */
    public int $totalPages;

    /**
     * @var bool The flag indicates whether has previous page or not
     */
    public bool $hasPreviousPage;

    /**
     * @var bool The flag indicates whether has next page or not
     */
    public bool $hasNextPage;

    /**
     * @var iterable The entity results
     */
    public iterable $results;

    /**
     * The constructor
     * 
     * @param int $pageNumber The current page number
     * @param int $limit The page limit
     * @param int $count The total data of current page
     * @param iterable $results The entity results
     */
    public function __construct(int $pageNumber, int $limit, int $count, iterable $results)
    {
        $this->pageNumber = $pageNumber;
        $this->limit = $limit;
        $this->count = iterator_count(new ArrayIterator($results));
        $this->totalCount = $count;
        $this->totalPages = (int)ceil($this->totalCount / $this->limit);
        $this->hasPreviousPage =  $pageNumber == 1 ? false : true;
        $this->hasNextPage = $pageNumber == $this->totalPages ? false : true;
        $this->results = $results;
    }
}
