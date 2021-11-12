<?php

namespace App\Messages\Responses\Users;

use App\Data\Views\PagedView;
use App\Messages\Responses\PagedResponse;

/**
 * Responder for users data with pagination.
 */
class UserPagedResponse
{
    /**
     * @var PagedResponse The page info for pagination.
     */
    public PagedResponse $pageInfo;

    /**
     * @var iterable The iterable of @see User.
     */
    public iterable $results;

    /**
     * The constructor
     * 
     * @param PagedView $pagedView The paged view
     */
    public function __construct(PagedView $pagedView)
    {
        $this->pageInfo = new PagedResponse($pagedView);

        if ($pagedView->count > 0) {
            foreach ($pagedView->results as $user) {
                $this->results[] = new UserDetailResponse($user);
            }
        } else {
            $this->results = [];
        }
    }
}
