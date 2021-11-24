<?php

declare(strict_types=1);

namespace App\Data;

use Cycle\Annotated\Annotation\Column;

/**
 * The generic data log entity.
 */
class DataLogEntity
{
    /**
     * @Column(type="datetime", name="created_at", default="now")
     * 
     * @var \DateTimeImmutable The resource created at timestamp.
     */
    public ?\DateTimeImmutable $createdAt = null;

    /**
     * @Column(type="datetime", name="updated_at", default="now")
     * 
     * @var \DateTimeImmutable The resource updated at timestamp.
     */
    public ?\DateTimeImmutable $updatedAt = null;
}
