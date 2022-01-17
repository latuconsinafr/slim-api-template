<?php

declare(strict_types=1);

namespace App\Messages\Responses\Users;

use App\Data\Paginated;

/**
 * The user response with pagination.
 */
class UserPaginatedResponse extends Paginated
{
    public function __construct(Paginated $paginated)
    {
        $this->paginatedInfo = $paginated->paginatedInfo;

        if ($paginated->paginatedInfo->count > 0) {
            foreach ($paginated->results as $user) {
                $this->results[] = new UserDetailResponse($user);
            }
        }
    }
}
