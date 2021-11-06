<?php

namespace App\Messages\Responses\Users;

use App\Data\Views\PagedView;
use App\Messages\Responses\PagedResponse;

class UserPagedResponse
{
    public PagedResponse $pageInfo;
    public iterable $results;

    public function __construct(PagedView $pagedView)
    {
        $this->pageInfo = new PagedResponse($pagedView->limit, $pagedView->pageNumber, $pagedView->count);

        foreach ($pagedView->result as $user) {
            $this->results[] = new UserDetailResponse($user);
        }
    }
}
