<?php

namespace App\Messages\Responses;

/**
 * Responder for pagination.
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
     * @var int The total items in current page.
     */
    public int $count;

    /**
     * The constructor
     * 
     * @param int $limit The page limit.
     * @param int $pageNumber The current page number.
     * @param int $count The total items in current page.
     */
    public function __construct(int $limit, int $pageNumber, int $count)
    {
        $this->limit = $limit;
        $this->pageNumber = $pageNumber;
        $this->count = $count;
    }
}
