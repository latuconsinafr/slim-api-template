<?php

declare(strict_types=1);

namespace App\Data\Entities;

use App\Data\DataLogEntity;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity(
 *  table="users", 
 *  mapper="App\Data\Mappers\TimestampedMapper"
 * )
 * @Table(
 *  indexes={
 *      @Index(columns = {"user_name"}, unique = true),
 *      @Index(columns = {"email"}, unique = true),
 *      @Index(columns = {"phone_number"}, unique = true),
 *  }
 * )
 */
class UserEntity extends DataLogEntity
{
    /**
     * @Column(type="string(36)", primary=true, typecast=App\Data\TypeCasts\Uuid::class)
     * 
     * @var UuidInterface The user's id.
     */
    public UuidInterface $id;

    /**
     * @Column(type="string(32)", name="user_name")
     * 
     * @var string The user's user name.
     */
    public string $userName;

    /**
     * @Column(type="string(64)", nullable=true)
     * 
     * @var string|null The user's e-mail address, if any.
     */
    public ?string $email = null;

    /**
     * @Column(type="string(32)", name="phone_number", nullable=true)
     * 
     * @var string|null The user's phone number, if any.
     */
    public ?string $phoneNumber = null;

    /**
     * @Column(type="string(128)")
     * 
     * @var string The user's password.
     */
    public string $password;
}
