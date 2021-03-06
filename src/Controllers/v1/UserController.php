<?php

declare(strict_types=1);

namespace App\Controllers\v1;

use App\Data\Entities\UserEntity;
use App\Data\TypeCasts\Uuid;
use App\Messages\Params\PaginatedParam;
use App\Messages\Requests\Users\UserCreateRequest;
use App\Messages\Requests\Users\UserUpdateRequest;
use App\Messages\Responses\Users\UserDetailResponse;
use App\Messages\Responses\Users\UserPaginatedResponse;
use App\Services\UserService;
use App\Supports\Loggers\Logger;
use App\Supports\Responders\ApiResponder;
use App\Validators\UserValidator;
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
     * @var UserService The user service.
     */
    private UserService $userService;

    /**
     * @var UserValidator The user validator.
     */
    private UserValidator $userValidator;

    /**
     * @var LoggerInterface The logger interface.
     */
    private LoggerInterface $logger;

    /**
     * The constructor.
     * 
     * @param ApiResponder $responder The api generic responder.
     * @param UserService $userService The user service.
     * @param UserValidator $userValidator The user validator.
     * @param Logger $logger The generic logger.
     */
    public function __construct(ApiResponder $responder, UserService $userService, UserValidator $userValidator, Logger $logger)
    {
        $this->responder = $responder;
        $this->userService = $userService;
        $this->userValidator = $userValidator;
        $this->logger = $logger->addFileHandler()
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

        $queryParams = (array)$request->getQueryParams();

        $paginatedParam = new PaginatedParam();

        $paginatedParam->limit = isset($queryParams['limit']) && $queryParams['limit'] > 0 ? (int)$queryParams['limit'] : 5;
        $paginatedParam->pageNumber = isset($queryParams['pageNumber']) && $queryParams['pageNumber'] > 0 ? (int)$queryParams['pageNumber'] : 1;
        $paginatedParam->orderByKey = isset($queryParams['orderByKey']) ? $queryParams['orderByKey'] : 'createdAt';
        $paginatedParam->orderByMethod = isset($queryParams['orderByMethod']) ? $queryParams['orderByMethod'] : 'ASC';
        $paginatedParam->search = isset($queryParams['search']) ? $queryParams['search'] : '';

        return $this->responder->OK($response, new UserPaginatedResponse(
            $this->userService->findAllWithQuery($paginatedParam)
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

        $id = Uuid::fromString($args['id']);
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

        $this->userValidator->validateCreateRequest((array)$request->getParsedBody());

        $request = new UserCreateRequest((array)$request->getParsedBody());

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

        $this->userValidator->validateUpdateRequest((array)$request->getParsedBody());

        $id = Uuid::fromString($args['id']);
        $request = new UserUpdateRequest((array)$request->getParsedBody());

        if ($id != $request->id) {
            $this->logger->warning("Request conflict with id {$id}.");

            return $this->responder->Conflict($response);
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

        $id = Uuid::fromString($args['id']);
        $user = $this->userService->findById($id);

        if (!$user instanceof UserEntity) {
            $this->logger->warning("User with id {$id} not found.");

            return $this->responder->NotFound($response);
        }

        $this->userService->delete($id);

        return $this->responder->OK($response);
    }
}
