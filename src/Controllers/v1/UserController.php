<?php

namespace App\Controllers\v1;

use App\Data\Entities\User;
use App\Messages\Requests\UserCreateRequest;
use App\Messages\Requests\UserUpdateRequest;
use App\Messages\Responses\Users\UserDetailResponse;
use App\Messages\Responses\Users\UserPagedResponse;
use App\Services\UserService;
use App\Validators\Users\UserCreateRequestValidator;
use App\Validators\Users\UserUpdateRequestValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\Validation\Exception\ValidationException;

/**
 * User Controller
 */
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
    public function getUsers(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();

        $limit = (isset($queryParams['limit']) && $queryParams > 0) ? $queryParams['limit'] : 5;
        $pageNumber = (isset($queryParams['pageNumber']) && $queryParams > 0) ? $queryParams['pageNumber'] : 1;

        $response
            ->getBody()
            ->write((string)json_encode(
                new UserPagedResponse(
                    $this->userService->getUsers($limit, $pageNumber),
                    $limit,
                    $pageNumber
                )
            ));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
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
    public function getUserById(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $user = $this->userService->getUserById($id);

        if (!$user instanceof User) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $response
            ->getBody()
            ->write((string)json_encode(
                new UserDetailResponse($user)
            ));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * The create endpoint
     * 
     * @param Request $request The request
     * @param Response $response The response
     * 
     * @return Response
     */
    public function createUser(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $validationResult = UserCreateRequestValidator::validate($data);

        if ($validationResult->fails()) {
            throw new ValidationException('Validation failed. Please check your input.', $validationResult);
        }

        $this->userService->createUser(new UserCreateRequest($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    /**
     * The update endpoint
     * 
     * @param Request $request The request
     * @param Response $response The response
     * @param array $args The query parameters
     * 
     * @return Response
     */
    public function updateUser(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $validationResult = UserUpdateRequestValidator::validate($data);

        if ($validationResult->fails()) {
            throw new ValidationException('Validation failed. Please check your input.', $validationResult);
        }

        $id = $args['id'];
        $user = $this->userService->getUserById($id);

        if (!$user instanceof User) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        if ($user->getId() != $id) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(409);
        }

        $this->userService->updateUser(new UserUpdateRequest($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    /**
     * The delete endpoint
     * 
     * @param Request $request The request
     * @param Response $response The response
     * @param array $args The query parameters
     * 
     * @return Response
     */
    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $user = $this->userService->getUserById($id);

        if (!$user instanceof User) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->userService->deleteUser($id);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
