<?php

declare(strict_types=1);

namespace App\Data\Entities;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use Ramsey\Uuid\Uuid;

/**
 * @Entity(
 *  table="users", 
 *  mapper="App\Data\Mappers\TimestampedMapper"
 * )
 * @Table(
 *  indexes={
 *      @Index(columns = {"user_name"}, unique = true),
 *      @Index(columns = {"email"}, unique = true),
 *      @Index(columns = {"phone_number"}, unique = true)
 *  }
 * )
 */
class UserEntity extends DataLogEntity
{
    /**
     * @Column(type="string(36)", primary=true)
     * @var string
     */
    protected string $id;

    /**
     * @Column(type="string(32)", name="user_name", nullable=false)
     * @var string
     */
    protected string $userName;

    /**
     * @Column(type="string(128)", nullable=true)
     * @var string
     */
    protected ?string $email;

    /**
     * @Column(type="string(32)", name="phone_number", nullable=true)
     * @var string
     */
    protected ?string $phoneNumber;

    /**
     * @Column(type="string(128)", nullable=false)
     * @var string
     */
    protected string $password;

    /**
     * The constructor.
     * 
     * @param int $id The user's id.
     * @param string $userName The user's user name.
     * @param string|null $email The user's e-mail.
     * @param string|null $phoneNumber The user's phone number.
     * @param string $password The user's password.
     */
    public function __construct(string $userName, ?string $email, ?string $phoneNumber, string $password, ?string $id = null)
    {
        $this->userName = strtolower($userName);
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->password = $password;
        $this->id = $id ? $id : UUID::uuid4()->toString();
    }

    /**
     * The id getter.
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * The user name getter.
     * 
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * The e-mail getter.
     * 
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * The phone number getter.
     * 
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * The password getter.
     * 
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * The id setter.
     * 
     * @param string $id The id to set to the user entity.
     * 
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * The user name setter.
     * 
     * @param string $userName The user name to set to the user entity.
     * 
     * @return void
     */
    public function setUserName(string $userName): void
    {
        $this->userName = strtolower($userName);
    }

    /**
     * The email setter.
     * 
     * @param string $email The email to set to the user entity.
     * 
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * The phone number setter.
     * 
     * @param string $phoneNumber The phone number to set to the user entity.
     * 
     * @return void
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * The password setter.
     * 
     * @param string $password The password to set to the user entity.
     * 
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
