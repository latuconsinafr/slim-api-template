<?php

declare(strict_types=1);

namespace App\Messages\Responses;

use App\Data\Paged;

/**
 * The paged response.
 */
class PagedResponse
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
    public int $count;

    /**
     * @var int The total data.
     */
    public int $totalCount;

    /**
     * @var int The total pages.
     */
    public int $totalPages;

    /**
     * @var bool The flag indicates whether has previous page or not.
     */
    public bool $hasPreviousPage;

    /**
     * @var bool The flag indicates whether has next page or not.
     */
    public bool $hasNextPage;

    /**
     * The constructor.
     * 
     * @param Paged $paged The paged data.
     */
    public function __construct(Paged $paged)
    {
        $this->pageNumber = $paged->pageNumber;
        $this->limit = $paged->limit;
        $this->count = $paged->count;
        $this->totalCount = $paged->totalCount;
        $this->totalPages = $paged->totalPages;
        $this->hasPreviousPage = $paged->hasPreviousPage;
        $this->hasNextPage = $paged->hasNextPage;
    }
}
