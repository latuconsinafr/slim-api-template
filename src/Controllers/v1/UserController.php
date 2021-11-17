<?php

declare(strict_types=1);

namespace App\Controllers\v1;

use App\Data\Entities\UserEntity;
use App\Messages\Requests\Users\UserCreateRequest;
use App\Messages\Requests\Users\UserUpdateRequest;
use App\Messages\Responses\Users\UserDetailResponse;
use App\Messages\Responses\Users\UserPagedResponse;
use App\Services\UserService;
use App\Supports\Loggers\Logger;
use App\Supports\Responders\ApiResponder;
use App\Validators\Users\UserCreateRequestValidator;
use App\Validators\Users\UserUpdateRequestValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

/**
 * User Controller.
 */
final class UserController
{
    /**
     * @var ApiResponder The generic api responder.
     */
    private ApiResponder $responder;

    /**
     * @var UserService The user repository.
     */
    private UserService $userService;

    /**
     * @var LoggerInterface The logger interface.
     */
    private LoggerInterface $logger;

    /**
     * The constructor.
     * 
     * @param Responder $responder The generic responder.
     * @param UserService $userService The user service.
     * @param Logger $logger The generic logger.
     */
    public function __construct(ApiResponder $responder, UserService $userService, Logger $logger)
    {
        $this->responder = $responder;
        $this->userService = $userService;
        $this->logger = $logger->addFileHandler('user.log')
            ->addConsoleHandler()
            ->createLogger();
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
        $this->logger->info("Try to get users.");

        $$queryParams = (array)$request->getQueryParams();
        $limit = isset($queryParams['limit']) && $queryParams['limit'] > 0 ? (int)$queryParams['limit'] : 5;
        $pageNumber = isset($queryParams['pageNumber']) && $queryParams['pageNumber'] > 0 ? (int)$queryParams['pageNumber'] : 1;

        return $this->responder->OK($response, new UserPagedResponse(
            $this->userService->findAllWithQuery($limit, $pageNumber)
        ));
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
        $this->logger->info("Try to get user.");

        $id = $args['id'];
        $user = $this->userService->findById($id);

        if (!$user instanceof UserEntity) {
            $this->logger->warning("User with id {$id} not found.");

            return $this->responder->NotFound($response);
        }

        return $this->responder->OK($response, new UserDetailResponse($user));
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
        $this->logger->info("Try to create user.");

        $request = new UserCreateRequest((array)$request->getParsedBody());
        $validationResult = (new UserCreateRequestValidator($request))->validate();

        if ($validationResult->fails()) {
            $this->logger->warning("Validation failed with request: " . json_encode($request->request));

            return $this->responder->UnprocessableEntity($response, $validationResult);
        }

        $this->userService->create($request->toEntity());

        return $this->responder->Created($response);
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
        $this->logger->info("Try to update user.");

        $request = new UserUpdateRequest($request->getParsedBody());
        $validationResult = (new UserUpdateRequestValidator($request))->validate();

        if ($validationResult->fails()) {
            $this->logger->warning("Validation failed with request: " . json_encode($request->request));

            return $this->responder->UnprocessableEntity($response, $validationResult);
        }

        $id = $args['id'];

        if ($request->request[$request->id] != $id) {
            $this->logger->warning("Request conflict with id {$id}.");

            return $this->responder->Conflict($response);
        }

        $user = $this->userService->findById($id);

        if (!$user instanceof UserEntity) {
            $this->logger->warning("User with id {$id} not found.");

            return $this->responder->NotFound($response);
        }

        $this->userService->update($request->toEntity());

        return $this->responder->OK($response);
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
        $this->logger->info("Try to delete user.");

        $id = $args['id'];
        $user = $this->userService->findById($id);

        if (!$user instanceof UserEntity) {
            $this->logger->warning("User with id {$id} not found.");

            return $this->responder->NotFound($response);
        }

        $this->userService->delete($id);

        return $this->responder->OK($response);
    }
}
