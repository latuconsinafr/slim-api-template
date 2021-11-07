<?php

use App\Controllers\HomeController;
use App\Controllers\v1\UserController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;

// Define app routes
return function (App $app) {

    // Default route
    $app->get('/', [HomeController::class, 'index']);

    // Application route list : api/v1
    $app->group(
        '/api/v1',
        function (Group $app) {
            // Users
            $app->get('/users', [UserController::class, 'getUsers']);
            $app->post('/users', [UserController::class, 'createUser']);
            $app->get('/users/{id}', [UserController::class, 'getUserById']);
            $app->put('/users/{id}', [UserController::class, 'updateUser']);
            $app->delete('/users/{id}', [UserController::class, 'deleteUser']);
        }
    );
};
