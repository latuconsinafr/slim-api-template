<?php

namespace App\Messages\Responses;

use App\Data\Views\PagedView;

/**
 * Responder for pagination.
 */
class PagedResponse
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
     * The constructor
     * 
     * @param PagedView $pagedView The paged view
     */
    public function __construct(PagedView $pagedView)
    {
        $this->pageNumber = $pagedView->pageNumber;
        $this->limit = $pagedView->limit;
        $this->count = $pagedView->count;
        $this->totalCount = $pagedView->totalCount;
        $this->totalPages = $pagedView->totalPages;
        $this->hasPreviousPage = $pagedView->hasPreviousPage;
        $this->hasNextPage = $pagedView->hasNextPage;
    }
}
