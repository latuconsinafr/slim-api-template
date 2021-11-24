<?php

declare(strict_types=1);

namespace App\Data\TypeCasts;

use Ramsey\Uuid\Uuid as UuidBody;
use Ramsey\Uuid\UuidInterface as UuidBodyInterface;
use Spiral\Database\DatabaseInterface;

/**
 * The class for uuid data type.
 */
class Uuid
{
    /**
     * @var UuidBodyInterface The uuid.
     */
    private UuidBodyInterface $uuid;

    /**
     * The uuid to string converter.
     * 
     * @return string The stringify uuid.
     */
    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * The string to uuid converter.
     * 
     * @param string $value The string value.
     * 
     * @return UuidBodyInterface The resulted uuid.
     */
    public static function fromString(string $value): UuidBodyInterface
    {
        return UuidBody::fromString((string)$value);
    }

    /**
     * The uuid create method.
     * 
     * @return UuidBodyInterface The resulted uuid.
     */
    public static function create(): UuidBodyInterface
    {
        return UuidBody::uuid4();
    }

    /**
     * The uuid typecast method for the database interface.
     * 
     * @param mixed $value The value to typecast.
     * @param DatabaseInterface $db The database interface.
     * 
     * @return UuidBodyInterface The resulted uuid.
     */
    public static function typecast(mixed $value, DatabaseInterface $db): UuidBodyInterface
    {
        return UuidBody::fromString((string)$value);
    }
}
