<?php

declare(strict_types=1);

namespace App\Supports\Validators;

use App\Supports\Responders\StatusMessageInterface;
use Cake\Validation\Validator as CakeValidator;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\Factory\CakeValidationFactory;

/**
 * Validator.
 */
class Validator
{
    /**
     * @var array The incoming request.
     */
    public array $request;

    /**
     * @var CakeValidationFactory The cake validation factory.
     */
    protected CakeValidationFactory $validationFactory;

    /**
     * @var CakeValidator The cake validator.
     */
    protected CakeValidator $validator;

    /**
     * The constructor.
     * 
     * @param array $request The incoming request.
     */
    public function __construct(array $request)
    {
        $this->request = $request;
        $this->validationFactory = new CakeValidationFactory();
        $this->validator = $this->validationFactory->createValidator();
    }

    /**
     * Validate the incoming request.
     * 
     * @return void
     */
    public function validate(): void
    {
        $validationResult = $this->validationFactory->createValidationResult(
            $this->validator->validate($this->request)
        );

        if ($validationResult->fails()) {
            throw new ValidationException(StatusMessageInterface::STATUS_UNPROCESSABLE_ENTITY, $validationResult);
        }
    }
}
