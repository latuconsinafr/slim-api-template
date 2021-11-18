<?php

declare(strict_types=1);

namespace App\Data\Entities;

use DateTimeImmutable;

class DataLogEntity
{
    protected DateTimeImmutable $created_at;
    protected DateTimeImmutable $updated_at;

    /**
     * The created at getter.
     * 
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * The update at getter.
     * 
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }
}
