<?php

namespace App\Validators\Users;

use App\Messages\Requests\Users\UserUpdateRequest;
use App\Validators\BaseValidator;

/**
 * The validator for user update request.
 */
class UserUpdateRequestValidator extends BaseValidator
{
    /**
     * @var UserUpdateRequest The request collection to validate.
     */
    protected UserUpdateRequest $requestCollection;

    /**
     * The constructor.
     * 
     * @param UserUpdateRequest $request The request collection to validate.
     */
    public function __construct(UserUpdateRequest $requestCollection)
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
            ->requirePresence($this->requestCollection->id);

        $this->validator
            ->requirePresence($this->requestCollection->userName);

        $this->validator
            ->requirePresence($this->requestCollection->password);
    }
}
