<?php

namespace App\Controllers\v1;

use App\Messages\Responses\Users\UserDetailResponse;
use App\Messages\Responses\Users\UserPagedResponse;
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
    public function getAll(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();
        
        $limit = (isset($queryParams['limit']) && $queryParams > 0) ? $queryParams['limit'] : 5;
        $pageNumber = (isset($queryParams['pageNumber']) && $queryParams > 0) ? $queryParams['pageNumber'] : 1;
        
        $users = new UserPagedResponse($this->userService->getAllWithPagination($limit, $pageNumber));
        $response->getBody()->write((string)json_encode($users));

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
        $id = $args['id'];

        $user = new UserDetailResponse($this->userService->get($id));
        $response->getBody()->write((string)json_encode($user));

        return $response;
    }
}
