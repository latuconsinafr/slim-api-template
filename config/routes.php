<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

// Define app routes
return function (App $app) {

    // Default route
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Hello world!");

        return $response;
    });
};
