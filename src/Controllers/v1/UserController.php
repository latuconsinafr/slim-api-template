<?php

namespace App\Controllers\v1;

use App\Data\Entities\User;
use App\Messages\Requests\Users\UserCreateRequest;
use App\Messages\Requests\Users\UserUpdateRequest;
use App\Messages\Responses\Users\UserDetailResponse;
use App\Messages\Responses\Users\UserPagedResponse;
use App\Repositories\Users\UserRepository;
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
     * @var UserRepository The user repository.
     */
    private UserRepository $userRepository;

    /**
     * The constructor.
     * 
     * @param UserRepository $userRepository.
     */
    public function __construct(UserRepository $userRepository)
    {
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

        $response
            ->getBody()
            ->write((string)json_encode(
                new UserPagedResponse(
                    $this->userRepository->findAll($limit, $pageNumber),
                    $limit,
                    $pageNumber
                )
            ));

        return $response
            ->withHeader('Content-Type', 'application/json')
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

        return $response
            ->withHeader('Content-Type', 'application/json')
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
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(409);
        }

        $user = $this->userRepository->findById($id);

        if (!$user instanceof User) {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->userRepository->update($request->toEntity());

        return $response
            ->withHeader('Content-Type', 'application/json')
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
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $this->userRepository->delete($id);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
