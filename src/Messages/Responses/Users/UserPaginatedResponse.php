<?php

declare(strict_types=1);

namespace App\Messages\Responses\Users;

use App\Data\Paginated;

/**
 * Responder for paginated user data.
 */
class UserPaginatedResponse extends Paginated
{
    /**
     * The constructor.
     * 
     * @param Paginated $paginated The paginated result.
     */
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
