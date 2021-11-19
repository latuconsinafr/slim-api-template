<?php

declare(strict_types=1);

namespace App\Supports\Responders;

interface StatusMessageInterface
{
    // Client Errors 4xx
    const STATUS_BAD_REQUEST = "The request cannot be fulfilled due to bad syntax.";
    const STATUS_NOT_FOUND = "The requested resource could not be found.";
    const STATUS_CONFLICT = "The request could not be processed because of conflict in the request.";
    const STATUS_PRECONDITION_FAILED = "The server does not meet one of the precondition on the request.";
    const STATUS_UNPROCESSABLE_ENTITY = "The request was unable to be followed due to semantic errors.";
    // Server Errors 5xx
    const STATUS_INTERNAL_SERVER_ERROR = "Unexpected internal server error.";
    const STATUS_SERVICE_UNAVAILABLE = "The server is currently unavailable.";
}
