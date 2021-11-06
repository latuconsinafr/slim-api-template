<?php

namespace App\Data\Views;

use ArrayIterator;

class PagedView
{
    public iterable $result;
    public int $limit;
    public int $pageNumber;
    public int $count;

    public function __construct(iterable $result, int $limit, int $pageNumber)
    {
        $this->result = $result;
        $this->limit = $limit;
        $this->pageNumber = $pageNumber;
        $this->count = iterator_count(new ArrayIterator($result));
    }
}
