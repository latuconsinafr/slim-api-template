<?php

declare(strict_types=1);

namespace App\Data\Entities;

use DateTimeImmutable;

/**
 * The generic data log entity.
 */
class DataLogEntity
{
    /**
     * @var DateTimeImmutable The resource created at timestamp.
     */
    protected DateTimeImmutable $created_at;

    /**
     * @var DateTimeImmutable The resource updated at timestamp.
     */
    protected DateTimeImmutable $updated_at;

    /**
     * The created at getter.
     * 
     * @return DateTimeImmutable The timestamp.
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * The update at getter.
     * 
     * @return DateTimeImmutable The timestamp.
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }
}
