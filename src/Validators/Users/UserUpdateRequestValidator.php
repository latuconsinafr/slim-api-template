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
     * @var UserUpdateRequest The request to validate.
     */
    protected UserUpdateRequest $request;

    /**
     * The constructor.
     * 
     * @param UserUpdateRequest $request The request to validate.
     */
    public function __construct(UserUpdateRequest $request)
    {
        parent::__construct($request->request);

        $this->request = $request;
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
            ->requirePresence($this->request->id);

        $this->validator
            ->requirePresence($this->request->userName)
            ->lengthBetween($this->request->userName, [4, 16])
            ->alphaNumeric($this->request->userName);

        $this->validator
            ->email($this->request->email);

        $this->validator
            ->requirePresence($this->request->password)
            ->lengthBetween($this->request->password, [4, 16]);
    }
}
