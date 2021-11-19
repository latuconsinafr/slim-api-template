<?php

declare(strict_types=1);

namespace App\Validators\Users;

use App\Messages\Requests\Users\UserCreateRequest;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\Factory\CakeValidationFactory;

final class UserCreateRequestValidator extends UserCreateRequest
{
    public function __construct(array $request)
    {
        $validationFactory = new CakeValidationFactory();
        $validator = $validationFactory->createValidator();

        $validator
            ->requirePresence('userName')
            ->notEmptyString('userName')
            ->lengthBetween('userName', [4, 16])
            ->alphaNumeric('userName');

        $validator
            ->notEmptyString('email')
            ->email('email');

        $validator
            ->notEmptyString('phoneNumber');

        $validator
            ->requirePresence('password')
            ->notEmptyString('password')
            ->lengthBetween('password', [4, 16]);

        // Convert validator errors to ValidationResult
        $validationResult = $validationFactory->createValidationResult(
            $validator->validate($request)
        );

        if ($validationResult->fails()) {
            throw new ValidationException('Validation failed. Please check your input.', $validationResult, 422);
        }

        foreach ($request as $key => $value) {
            $this->$key = $value;
        }
    }
}
