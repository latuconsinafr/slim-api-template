<?php

declare(strict_types=1);

namespace App\Messages\Params;

/**
 * Paginated param for pagination.
 */
class PaginatedParam
{
    /**
     * @var int|null The page limit.
     */
    public ?int $limit;

    /**
     * @var int|null The current page number.
     */
    public ?int $pageNumber;

    /**
     * @var string|null
     */
    public ?string $orderByKey;

    /**
     * @var string|null
     */
    public ?string $orderByMethod;

    /**
     * @var string|null
     */
    public ?string $search;
}
