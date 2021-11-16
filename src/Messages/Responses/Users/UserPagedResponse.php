<?php

declare(strict_types=1);

namespace App\Messages\Responses\Users;

use App\Data\Paged;
use App\Messages\Responses\PagedResponse;

/**
 * The user response with pagination.
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
     * The constructor.
     * 
     * @param Paged $paged The paged data.
     */
    public function __construct(Paged $paged)
    {
        $this->pageInfo = new PagedResponse($paged);

        if ($paged->count > 0) {
            foreach ($paged->results as $user) {
                $this->results[] = new UserDetailResponse($user);
            }
        } else {
            $this->results = [];
        }
    }
}
