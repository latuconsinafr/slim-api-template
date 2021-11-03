<?php

use Slim\App;
use App\Controllers\HomeController;

// Define app routes
return function (App $app) {

    // Default route
    $app->get('/', [HomeController::class, 'index']);
};
