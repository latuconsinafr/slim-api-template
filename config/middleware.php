<?php

use Selective\Validation\Middleware\ValidationExceptionMiddleware;
use Slim\App;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(ValidationExceptionMiddleware::class);
};
