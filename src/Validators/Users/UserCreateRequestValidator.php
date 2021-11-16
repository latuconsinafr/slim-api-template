<?php

declare(strict_types=1);

namespace App\Validators\Users;

use App\Messages\Requests\Users\UserCreateRequest;
use App\Validators\BaseValidator;

/**
 * The validator for user create request.
 */
class UserCreateRequestValidator extends BaseValidator
{
    /**
     * @var UserCreateRequest The request to validate.
     */
    protected UserCreateRequest $request;

    /**
     * The constructor.
     * 
     * @param UserCreateRequest $request The request to validate.
     */
    public function __construct(UserCreateRequest $request)
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
