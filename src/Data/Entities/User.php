<?php

namespace App\Data\Entities;

class User
{
    /**
     * @var string The user's uuid
     */
    public string $id;

    /**
     * @var string The user's username
     */
    public string $userName;

    /**
     * @var string The user's e-mail
     */
    public ?string $email;

    /**
     * @var string The user's phone number
     */
    public ?string $phoneNumber;

    /**
     * @var string The user's password
     */
    private string $password;

    /**
     * The constructor
     */
    public function __construct()
    {
    }

    /**
     * The id getter
     * 
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * The user name getter
     * 
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * The e-mail getter
     * 
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * The phone number getter
     * 
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * The password getter
     * 
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * The id setter
     * 
     * @param string $id The id to set to the user entity
     * 
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * The user name setter
     * 
     * @param string $userName The user name to set to the user entity
     * 
     * @return void
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * The email setter
     * 
     * @param string $email The email to set to the user entity
     * 
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * The phone number setter
     * 
     * @param string $phoneNumber The phone number to set to the user entity
     * 
     * @return void
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * The password setter
     * 
     * @param string $password The password to set to the user entity
     * 
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * The entity setter
     * 
     * @param string $id The id to set to the user entity
     * @param string $userName The user name to set to the user entity
     * @param string $email The email to set to the user entity
     * @param string $phoneNumber The phone number to set to the user entity
     * @param string $password The password to set to the user entity
     * 
     * @return void
     */
    public function setEntity(
        string $id,
        string $userName,
        ?string $email,
        ?string $phoneNumber,
        string $password
    ): void {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->password = $password;
    }
}
