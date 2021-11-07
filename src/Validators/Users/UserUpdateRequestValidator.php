<?php

namespace App\Validators\Users;

use Selective\Validation\Factory\CakeValidationFactory;
use Selective\Validation\ValidationResult;

/**
 * Validator for user creation
 */
class UserUpdateRequestValidator
{
    /**
     * The constructor
     */
    public function __construct()
    {
    }

    /**
     * The validate data function
     * 
     * @param array $data The data to be validated
     * 
     * @return ValidationResult The validation result
     */
    public static function validate(array $data): ValidationResult
    {
        $validationFactory = new CakeValidationFactory();
        $validator = $validationFactory->createValidator();

        $validator
            ->requirePresence('userName');

        $validator
            ->requirePresence('password');

        return $validationFactory->createValidationResult(
            $validator->validate($data)
        );
    }
}
