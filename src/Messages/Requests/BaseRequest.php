<?php

namespace App\Messages\Requests;

/**
 * The base request.
 */
class BaseRequest
{
    /**
     * @var array The request.
     */
    public array $request;

    /**
     * The constructor.
     * 
     * @param array $request The incoming request.
     */
    public function __construct(array $request)
    {
        $this->request = $request;
    }
}
