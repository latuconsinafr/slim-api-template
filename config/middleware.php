<?php

declare(strict_types=1);

use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(ErrorMiddleware::class);
};
