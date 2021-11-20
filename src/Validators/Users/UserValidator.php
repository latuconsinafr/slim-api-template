<?php

declare(strict_types=1);

namespace App\Validators\Users;

use App\Data\Entities\UserEntity;
use App\Services\UserService;
use App\Supports\Responders\StatusMessageInterface;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\Factory\CakeValidationFactory;
use Selective\Validation\ValidationResult;

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
        $validationResult = $this->isUserNameExists($request, $validationResult, 'userName');
        $validationResult = $this->isEmailExists($request, $validationResult, 'email');
        $validationResult = $this->isPhoneNumberExists($request, $validationResult, 'phoneNumber');

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
        $validationResult = $this->isUserIdNotExists($request, $validationResult, 'id');
        $validationResult = $this->isUserNameExists($request, $validationResult, 'userName', 'id');
        $validationResult = $this->isEmailExists($request, $validationResult, 'email', 'id');
        $validationResult = $this->isPhoneNumberExists($request, $validationResult, 'phoneNumber', 'id');

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

    /**
     * The check if user's id is not exists validation.
     * 
     * @param array $request The incoming request.
     * @param ValidationResult $validationResult The validation result.
     * @param string $field The field name.
     * @param string $message The message The error message.
     * 
     * @return ValidationResult Add error to the validation result if the user's id not exists.
     */
    private function isUserIdNotExists(array $request, ValidationResult $validationResult, string $field, ?string $message = 'User not found.'): ValidationResult
    {
        if (!$this->userService->findById($request[$field]) instanceof UserEntity) {
            $validationResult->addError($field, $message);
        }

        return $validationResult;
    }

    /**
     * The check if user's user name exists validation.
     * 
     * @param array $request The incoming request.
     * @param ValidationResult $validationResult The validation result.
     * @param string $field The field name.
     * @param string $message The message The error message.
     * 
     * @return ValidationResult Add error to the validation result if the user's user name exists.
     */
    private function isUserNameExists(array $request, ValidationResult $validationResult, string $field, ?string $idField = null, ?string $message = 'User name is already taken.'): ValidationResult
    {
        $user = $this->userService->findByUserName($request[$field]);

        if ($user instanceof UserEntity xor (!is_null($idField) && ($user->getId() == $request[$idField]))) {
            $validationResult->addError($field, $message);
        }

        return $validationResult;
    }

    /**
     * The check if user's email exists validation.
     * 
     * @param array $request The incoming request.
     * @param ValidationResult $validationResult The validation result.
     * @param string $field The field name.
     * @param string $message The message The error message.
     * 
     * @return ValidationResult Add error to the validation result if the user's email exists.
     */
    private function isEmailExists(array $request, ValidationResult $validationResult, string $field, ?string $idField = null, ?string $message = 'Email is already taken.'): ValidationResult
    {
        $user = $this->userService->findByEmail($request[$field]);

        if ($user instanceof UserEntity xor (!is_null($idField) && ($user->getId() == $request[$idField]))) {
            $validationResult->addError($field, $message);
        }

        return $validationResult;
    }

    /**
     * The check if user's phone number exists validation.
     * 
     * @param array $request The incoming request.
     * @param ValidationResult $validationResult The validation result.
     * @param string $field The field name.
     * @param string $message The message The error message.
     * 
     * @return ValidationResult Add error to the validation result if the user's phone number exists.
     */
    private function isPhoneNumberExists(array $request, ValidationResult $validationResult, string $field, ?string $idField = null, ?string $message = 'Phone number is already taken.'): ValidationResult
    {
        $user = $this->userService->findByPhoneNumber($request[$field]);

        if ($user instanceof UserEntity xor (!is_null($idField) && ($user->getId() == $request[$idField]))) {
            $validationResult->addError($field, $message);
        }

        return $validationResult;
    }
}
