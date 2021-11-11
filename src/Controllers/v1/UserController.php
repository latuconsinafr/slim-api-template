<?php

namespace App\Controllers\v1;

use App\Data\Entities\User;
use App\Messages\Requests\Users\UserCreateRequest;
use App\Messages\Requests\Users\UserUpdateRequest;
use App\Messages\Responses\Users\UserDetailResponse;
use App\Messages\Responses\Users\UserPagedResponse;
use App\Repositories\Users\UserRepository;
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
     * @var Responder The generic responder
     */
    private Responder $responder;

    /**
     * @var UserRepository The user repository.
     */
    private UserRepository $userRepository;

    /**
     * The constructor.
     * 
     * @param UserRepository $userRepository.
     */
    public function __construct(Responder $responder, UserRepository $userRepository)
    {
        $this->responder = $responder;
        $this->userRepository = $userRepository;
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
        $queryParams = $request->getQueryParams();

        $limit = (isset($queryParams['limit']) && $queryParams > 0) ? $queryParams['limit'] : 5;
        $pageNumber = (isset($queryParams['pageNumber']) && $queryParams > 0) ? $queryParams['pageNumber'] : 1;

        $data = new UserPagedResponse($this->userRepository->findAll($limit, $pageNumber), $limit, $pageNumber);

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
        $user = $this->userRepository->findById($id);

        if (!$user instanceof User) {
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
        $request = new UserCreateRequest($request->getParsedBody());
        $validationResult = (new UserCreateRequestValidator($request))->validate();

        if ($validationResult->fails()) {
            throw new ValidationException('Validation failed. Please check your input.', $validationResult);
        }

        $this->userRepository->add($request->toEntity());

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

        if ($request->id != $id) {
            return $this->responder
                ->withJson($response)
                ->withStatus(409);
        }

        $user = $this->userRepository->findById($id);

        if (!$user instanceof User) {
            return $this->responder
                ->withJson($response)
                ->withStatus(404);
        }

        $this->userRepository->update($request->toEntity());

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
        $user = $this->userRepository->findById($id);

        if (!$user instanceof User) {
            return $this->responder
                ->withJson($response)
                ->withStatus(404);
        }

        $this->userRepository->delete($id);

        return $this->responder
            ->withJson($response)
            ->withStatus(200);
    }
}
