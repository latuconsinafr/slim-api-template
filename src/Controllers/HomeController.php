<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class HomeController
{

    /**
     * The constructor
     */
    public function __construct()
    {
    }

    /**
     * The index
     * 
     * @param Request $request The request
     * @param Response $response The response
     * @param array $args The query parameters
     * 
     * @return Response
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        return $response;
    }
}
