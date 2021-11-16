<?php

declare(strict_types=1);

namespace App\Controllers\v1;

use App\Data\Entities\UserEntity;
use App\Domain\User\User;
use App\Messages\Requests\Users\UserCreateRequest;
use App\Messages\Requests\Users\UserUpdateRequest;
use App\Messages\Responses\Users\UserDetailResponse;
use App\Messages\Responses\Users\UserPagedResponse;
use App\Services\UserService;
use App\Supports\Responders\Responder;
use App\Validators\Users\UserCreateRequestValidator;
use App\Validators\Users\UserUpdateRequestValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\Validation\Exception\ValidationException;

/**
 * User Controller.
 */
final class UserController
{
    /**
     * @var Responder The generic responder.
     */
    private Responder $responder;

    /**
     * @var UserService The user repository.
     */
    private UserService $userService;

    /**
     * The constructor.
     * 
     * @param Responder $responder The generic responder.
     * @param UserService $userService The user service.
     */
    public function __construct(Responder $responder, UserService $userService)
    {
        $this->responder = $responder;
        $this->userService = $userService;
    }

    /**
     * The get all endpoint.
     * 
     * @param Request $request The request.
     * @param Response $response The response.
     * @param array $args The query parameters.
     * 
     * @return Response The response.
     */
    public function getUsers(Request $request, Response $response): Response
    {
        $queryParams = (array)$request->getQueryParams();
        $limit = isset($queryParams['limit']) && $queryParams['limit'] > 0 ? (int)$queryParams['limit'] : 5;
        $pageNumber = isset($queryParams['pageNumber']) && $queryParams['pageNumber'] > 0 ? (int)$queryParams['pageNumber'] : 1;

        $data = new UserPagedResponse($this->userService->findAllWithQuery($limit, $pageNumber));

        return $this->responder
            ->withJson($response, $data)
            ->withStatus(200);
    }

    /**
     * The get by id endpoint.
     * 
     * @param Request $request The request.
     * @param Response $response The response.
     * @param array $args The query parameters.
     * 
     * @return Response The response.
     */
    public function getUserById(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $user = $this->userService->findById($id);

        if (!$user instanceof UserEntity) {
            return $this->responder
                ->withJson($response)
                ->withStatus(404);
        }

        $data = new UserDetailResponse($user);

        return $this->responder
            ->withJson($response, $data)
            ->withStatus(200);
    }

    /**
     * The create endpoint.
     * 
     * @param Request $request The request.
     * @param Response $response The response.
     * 
     * @return Response The response.
     */
    public function createUser(Request $request, Response $response): Response
    {
        $request = new UserCreateRequest((array)$request->getParsedBody());
        $validationResult = (new UserCreateRequestValidator($request))->validate();

        if ($validationResult->fails()) {
            throw new ValidationException('Validation failed. Please check your input.', $validationResult);
        }

        $this->userService->create($request->toEntity());

        return $this->responder
            ->withJson($response)
            ->withStatus(201);
    }

    /**
     * The update endpoint.
     * 
     * @param Request $request The request.
     * @param Response $response The response.
     * @param array $args The query parameters.
     * 
     * @return Response The response.
     */
    public function updateUser(Request $request, Response $response, array $args): Response
    {
        $request = new UserUpdateRequest($request->getParsedBody());
        $validationResult = (new UserUpdateRequestValidator($request))->validate();

        if ($validationResult->fails()) {
            throw new ValidationException('Validation failed. Please check your input.', $validationResult);
        }

        $id = $args['id'];

        if ($request->request[$request->id] != $id) {
            return $this->responder
                ->withJson($response)
                ->withStatus(409);
        }

        $user = $this->userService->findById($id);

        if (!$user instanceof UserEntity) {
            return $this->responder
                ->withJson($response)
                ->withStatus(404);
        }

        $this->userService->update($request->toEntity());

        return $this->responder
            ->withJson($response)
            ->withStatus(200);
    }

    /**
     * The delete endpoint.
     * 
     * @param Request $request The request.
     * @param Response $response The response.
     * @param array $args The query parameters.
     * 
     * @return Response The response.
     */
    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $user = $this->userService->findById($id);

        if (!$user instanceof UserEntity) {
            return $this->responder
                ->withJson($response)
                ->withStatus(404);
        }

        $this->userService->delete($id);

        return $this->responder
            ->withJson($response)
            ->withStatus(200);
    }
}
