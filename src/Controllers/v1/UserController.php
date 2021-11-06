<?php

namespace App\Controllers\v1;

use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UserController
{
    /**
     * @var UserService The user service
     */
    private UserService $userService;

    /**
     * The constructor
     * 
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * The get all endpoint
     * 
     * @param Request $request The request
     * @param Response $response The response
     * @param array $args The query parameters
     * 
     * @return Response
     */
    public function getAll(Request $request, Response $response, array $args): Response
    {
        $response->getBody()->write(json_encode($this->userService->getAll()));

        return $response;
    }

    /**
     * The get by id endpoint
     * 
     * @param Request $request The request
     * @param Response $response The response
     * @param array $args The query parameters
     * 
     * @return Response
     */
    public function get(Request $request, Response $response, array $args): Response
    {
        $user = $this->userService->get($args['id']);
        $response->getBody()->write(json_encode($user));

        return $response;
    }
}
