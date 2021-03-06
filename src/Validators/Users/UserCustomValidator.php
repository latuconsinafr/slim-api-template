<?php

declare(strict_types=1);

namespace App\Validators\Users;

use App\Data\Entities\UserEntity;
use App\Data\TypeCasts\Uuid;
use App\Services\UserService;
use Selective\Validation\ValidationResult;

/**
 * User custom validator.
 */
class UserCustomValidator implements UserCustomValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isUserIdNotExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $message = 'User not found'): ValidationResult
    {
        if (isset($request[$field]) && !$service->findById(Uuid::fromString($request[$field])) instanceof UserEntity) {
            $result->addError($field, $message);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function isUserNameExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $idField = null, ?string $message = 'User name is already taken'): ValidationResult
    {
        if (isset($request[$field])) {
            $user = $service->findByUserName($request[$field]);

            if ($user instanceof UserEntity xor (!is_null($idField) && ($user->id == $request[$idField]))) {
                $result->addError($field, $message);
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function isEmailExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $idField = null, ?string $message = 'Email is already taken'): ValidationResult
    {
        if (isset($request[$field])) {
            $user = $service->findByEmail($request[$field]);

            if ($user instanceof UserEntity xor (!is_null($idField) && ($user->id == $request[$idField]))) {
                $result->addError($field, $message);
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function isPhoneNumberExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $idField = null, ?string $message = 'Phone number is already taken'): ValidationResult
    {
        if (isset($request[$field])) {
            $user = $service->findByPhoneNumber($request[$field]);

            if ($user instanceof UserEntity xor (!is_null($idField) && ($user->id == $request[$idField]))) {
                $result->addError($field, $message);
            }
        }

        return $result;
    }
}
