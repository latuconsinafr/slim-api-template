<?php

declare(strict_types=1);

namespace App\Validators\Users;

use App\Services\UserService;
use Selective\Validation\ValidationResult;

/**
 * User custom validator interface.
 */
interface UserCustomValidatorInterface
{
    /**
     * The user not exists by id validator.
     * 
     * @param array $request The incoming array request.
     * @param string $field The id field to validate.
     * @param UserService $service The service to use to find the user.
     * @param ValidationResult $result The validation result.
     * @param string|null $message The error message.
     * 
     * @return ValidationResult The validation result.
     */
    public function isUserIdNotExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $message = 'User not found');

    /**
     * The user exists by user name validator.
     * 
     * @param array $request The incoming array request.
     * @param string $field The user name field to validate.
     * @param UserService $service The service to use to find the user.
     * @param ValidationResult $result The validation result.
     * @param string|null $idField The id field to validate, it assumes an update request if the id field is present, otherwise a create request.
     * @param string|null $message The error message.
     * 
     * @return ValidationResult The validation result.
     */
    public function isUserNameExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $idField = null, ?string $message = 'User name is already taken');

    /**
     * The user exists by email validator.
     * 
     * @param array $request The incoming array request.
     * @param string $field The email field to validate.
     * @param UserService $service The service to use to find the user.
     * @param ValidationResult $result The validation result.
     * @param string|null $idField The id field to validate, it assumes an update request if the id field is present, otherwise a create request.
     * @param string|null $message The error message.
     * 
     * @return ValidationResult The validation result.
     */
    public function isEmailExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $idField = null, ?string $message = 'Email address is already taken');

    /**
     * The user exists by phone number validator.
     * 
     * @param array $request The incoming array request.
     * @param string $field The phone number field to validate.
     * @param UserService $service The service to use to find the user.
     * @param ValidationResult $result The validation result.
     * @param string|null $idField The id field to validate, it assumes an update request if the id field is present, otherwise a create request.
     * @param string|null $message The error message.
     * 
     * @return ValidationResult The validation result.
     */
    public function isPhoneNumberExists(array $request, string $field, UserService $service, ValidationResult $result, ?string $idField = null, ?string $message = 'Phone number is already taken');
}
