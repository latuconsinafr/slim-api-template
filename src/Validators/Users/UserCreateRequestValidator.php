<?php

namespace App\Validators\Users;

use App\Messages\Requests\Users\UserCreateRequest;
use App\Validators\BaseValidator;

/**
 * The validator for user create request.
 */
class UserCreateRequestValidator extends BaseValidator
{
    /**
     * @var UserCreateRequest The request collection to validate.
     */
    protected UserCreateRequest $requestCollection;

    /**
     * The constructor.
     * 
     * @param UserCreateRequest $request The request collection to validate.
     */
    public function __construct(UserCreateRequest $requestCollection)
    {
        parent::__construct($requestCollection->request);

        $this->requestCollection = $requestCollection;
        $this->rules();
    }

    /**
     * Set the validation rules.
     * 
     * @return void
     */
    public function rules(): void
    {
        $this->validator
            ->requirePresence($this->requestCollection->userName);

        $this->validator
            ->requirePresence($this->requestCollection->password);
    }
}
