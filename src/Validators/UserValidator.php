<?php

declare(strict_types=1);

namespace App\Validators;

use App\Services\UserService;
use App\Supports\Responders\StatusMessageInterface;
use App\Validators\Users\UserCustomValidator;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\Factory\CakeValidationFactory;

/**
 * User validator.
 */
class UserValidator
{
    /**
     * @var CakeValidationFactory The cake validation factory.
     */
    protected CakeValidationFactory $validationFactory;

    /**
     * @var UserCustomValidator The user custom validator interface.
     */
    private UserCustomValidator $userCustomValidator;

    /**
     * @var UserService The user service.
     */
    private UserService $userService;

    /**
     * The constructor.
     * 
     * @param UserService $userService The user service.
     */
    public function __construct(UserService $userService)
    {
        $this->validationFactory = new CakeValidationFactory();
        $this->userCustomValidator = new UserCustomValidator();
        $this->userService = $userService;
    }

    /**
     * The user create request validator.
     * 
     * @param array $request The incoming request.
     * 
     * @return void
     */
    public function validateCreateRequest(array $request): void
    {
        $validator = $this->createRequestValidator($this->validationFactory->createValidator());
        $validationResult = $this->validationFactory->createValidationResult(
            $validator->validate($request)
        );
        $validationResult = $this->userCustomValidator->isUserNameExists($request, 'userName', $this->userService, $validationResult);
        $validationResult = $this->userCustomValidator->isEmailExists($request, 'email', $this->userService, $validationResult);
        $validationResult = $this->userCustomValidator->isPhoneNumberExists($request, 'phoneNumber', $this->userService, $validationResult);

        if ($validationResult->fails()) {
            throw new ValidationException(StatusMessageInterface::STATUS_UNPROCESSABLE_ENTITY, $validationResult);
        }
    }

    /**
     * The user create request validator.
     * 
     * @param array $request The incoming request.
     * 
     * @return void
     */
    public function validateUpdateRequest(array $request): void
    {
        $validator = $this->updateRequestValidator($this->validationFactory->createValidator());
        $validationResult = $this->validationFactory->createValidationResult(
            $validator->validate($request)
        );
        $validationResult = $this->userCustomValidator->isUserIdNotExists($request, 'id', $this->userService, $validationResult);
        $validationResult = $this->userCustomValidator->isUserNameExists($request, 'userName', $this->userService, $validationResult, 'id');
        $validationResult = $this->userCustomValidator->isEmailExists($request, 'email', $this->userService, $validationResult, 'id');
        $validationResult = $this->userCustomValidator->isPhoneNumberExists($request, 'phoneNumber', $this->userService, $validationResult, 'id');

        if ($validationResult->fails()) {
            throw new ValidationException(StatusMessageInterface::STATUS_UNPROCESSABLE_ENTITY, $validationResult);
        }
    }

    /**
     * Create request validator.
     *
     * @return Validator The validator.
     */
    private function createRequestValidator(Validator $validator): Validator
    {
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

        return $validator;
    }

    /**
     * Update request validator.
     *
     * @return Validator The validator.
     */
    private function updateRequestValidator(Validator $validator): Validator
    {
        $validator = $this->createRequestValidator($validator);

        $validator
            ->requirePresence('id')
            ->notEmptyString('id')
            ->uuid('id');

        return $validator;
    }
}