<?php

namespace App\Validators;

use Cake\Validation\Validator;
use Selective\Validation\Factory\CakeValidationFactory;
use Selective\Validation\ValidationResult;

/**
 * The base validator.
 */
class BaseValidator
{
    /**
     * @var CakeValidationFactory The validation factory.
     */
    protected CakeValidationFactory $validationFactory;

    /**
     * @var Validator The validator.
     */
    protected Validator $validator;

    /**
     * @var array The request to validate.
     */
    protected array $request;

    /**
     * The constructor.
     * 
     * @param array $request The request to validate.
     */
    public function __construct(array $request)
    {
        $this->request = $request;
        $this->validationFactory = new CakeValidationFactory();
        $this->validator = $this->validationFactory->createValidator();
    }

    /**
     * The validation function.
     * 
     * @return ValidationResult The validation result.
     */
    public function validate(): ValidationResult
    {
        return $this->validationFactory->createValidationResult(
            $this->validator->validate($this->request)
        );
    }
}
