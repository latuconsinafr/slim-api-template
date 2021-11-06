<?php

namespace App\Messages\Responses;

class PagedResponse
{
    public int $limit;
    public int $pageNumber;
    public int $count;

    public function __construct(int $limit, int $pageNumber, int $count)
    {
        $this->limit = $limit;
        $this->pageNumber = $pageNumber;
        $this->count = $count;
    }
}
