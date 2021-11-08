<?php

namespace App\Messages\Responses\Users;

use App\Messages\Responses\PagedResponse;
use ArrayIterator;

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
     * The constructor.
     * 
     * @param iterable $result The iterable of @see User.
     * @param int $limit The page limit.
     * @param int $pageNumber The current page number.
     */
    public function __construct(iterable $result, int $limit, int $pageNumber)
    {
        $count = iterator_count(new ArrayIterator($result));
        $this->pageInfo = new PagedResponse($limit, $pageNumber, $count);

        if ($count > 0) {
            foreach ($result as $user) {
                $this->results[] = new UserDetailResponse($user);
            }
        } else {
            $this->results = [];
        }
    }
}
