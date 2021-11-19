<?php

declare(strict_types=1);

namespace App\Validators\Users;

use App\Supports\Validators\Validator;

/**
 * Validator for user update request.
 */
class UserUpdateRequestValidator extends Validator
{
    /**
     * The constructor.
     * 
     * @param array $request The incoming request.
     */
    public function __construct(array $request)
    {
        parent::__construct($request);

        $this->validator
            ->requirePresence('id')
            ->notEmptyString('id')
            ->uuid('id');

        $this->validator
            ->requirePresence('userName')
            ->notEmptyString('userName')
            ->lengthBetween('userName', [4, 16])
            ->alphaNumeric('userName');

        $this->validator
            ->notEmptyString('email')
            ->email('email');

        $this->validator
            ->notEmptyString('phoneNumber');

        $this->validator
            ->requirePresence('password')
            ->notEmptyString('password')
            ->lengthBetween('password', [4, 16]);

        $this->validate();
    }
}
