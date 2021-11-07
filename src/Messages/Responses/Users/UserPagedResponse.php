<?php

namespace App\Messages\Responses\Users;

use App\Messages\Responses\PagedResponse;
use ArrayIterator;

class UserPagedResponse
{
    public PagedResponse $pageInfo;
    public iterable $results;

    public function __construct(iterable $result, int $limit, int $pageNumber)
    {
        $this->pageInfo = new PagedResponse($limit, $pageNumber, iterator_count(new ArrayIterator($result)));

        foreach ($result as $user) {
            $this->results[] = new UserDetailResponse($user);
        }
    }
}
