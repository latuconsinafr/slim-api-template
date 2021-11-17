<?php

declare(strict_types=1);

namespace App\Supports\Responders;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\ValidationResult;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\PhpRenderer;

/**
 * Api responder.
 */
final class ApiResponder extends Responder
{
    /**
     * The constructor.
     *
     * @param PhpRenderer $phpRenderer The template engine
     * @param RouteParserInterface $routeParser The route parser
     * @param ResponseFactoryInterface $responseFactory The response factory
     */
    public function __construct(
        PhpRenderer $phpRenderer,
        RouteParserInterface $routeParser,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->phpRenderer = $phpRenderer;
        $this->responseFactory = $responseFactory;
        $this->routeParser = $routeParser;
    }

    /**
     * The 200 OK status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param mixed|null $data The response data, if any.
     * 
     * @return ResponseInterface The response interface.
     */
    public function OK(ResponseInterface $response, mixed $data = null): ResponseInterface
    {
        return $this->withJson($response, $data)->withStatus(StatusCodeInterface::STATUS_OK);
    }

    /**
     * The 201 Created status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param mixed|null $data The response data, if any.
     * 
     * @return ResponseInterface The response interface.
     */
    public function Created(ResponseInterface $response, mixed $data = null): ResponseInterface
    {
        return $this->withJson($response, $data)->withStatus(StatusCodeInterface::STATUS_CREATED);
    }

    /**
     * The 204 No Content status code.
     * 
     * @param ResponseInterface $response The response interface.
     * 
     * @return ResponseInterface The response interface.
     */
    public function NoContent(ResponseInterface $response): ResponseInterface
    {
        return $this->withJson($response)->withStatus(StatusCodeInterface::STATUS_NO_CONTENT);
    }

    /**
     * The 400 Bad Request status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param string $message The error message.
     * 
     * @return ResponseInterface The response interface.
     */
    public function BadRequest(ResponseInterface $response, string $message = StatusMessageInterface::STATUS_BAD_REQUEST): ResponseInterface
    {
        return $this->withJson($response, $this->getErrorsResponse($message))->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);
    }

    /**
     * The 404 Not Found status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param string $message The error message.
     * 
     * @return ResponseInterface The response interface.
     */
    public function NotFound(ResponseInterface $response, string $message = StatusMessageInterface::STATUS_NOT_FOUND): ResponseInterface
    {
        return $this->withJson($response, $this->getErrorsResponse($message))->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
    }

    /**
     * The 409 Conflict status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param string $message The error message.
     * 
     * @return ResponseInterface The response interface.
     */
    public function Conflict(ResponseInterface $response, string $message = StatusMessageInterface::STATUS_CONFLICT): ResponseInterface
    {
        return $this->withJson($response, $this->getErrorsResponse($message))->withStatus(StatusCodeInterface::STATUS_CONFLICT);
    }

    /**
     * The 412 Precondition Failed status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param string $message The error message.
     * 
     * @return ResponseInterface The response interface.
     */
    public function PreconditionFailed(ResponseInterface $response, string $message = StatusMessageInterface::STATUS_PRECONDITION_FAILED): ResponseInterface
    {
        return $this->withJson($response, $this->getErrorsResponse($message))->withStatus(StatusCodeInterface::STATUS_PRECONDITION_FAILED);
    }

    /**
     * The 422 Unprocessable Entity status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param ValidationResult $validationResult The validation result.
     * @param string $message The error message.
     * 
     * @return ResponseInterface The response interface.
     */
    public function UnprocessableEntity(ResponseInterface $response, ValidationResult $validationResult = null, string $message = StatusMessageInterface::STATUS_UNPROCESSABLE_ENTITY): ResponseInterface
    {
        if ($validationResult instanceof ValidationResult) {
            throw new ValidationException("Input validation failed.", $validationResult);
        } else {
            return $this->withJson($response, $this->getErrorsResponse($message))->withStatus(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * The 500 Internal Server Error status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param string $message The error message.
     * 
     * @return ResponseInterface The response interface.
     */
    public function InternalServerError(ResponseInterface $response, string $message = StatusMessageInterface::STATUS_INTERNAL_SERVER_ERROR): ResponseInterface
    {
        return $this->withJson($response, $this->getErrorsResponse($message))->withStatus(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
    }

    /**
     * The 503 Service Unavailable status code.
     * 
     * @param ResponseInterface $response The response interface.
     * @param string $message The error message.
     * 
     * @return ResponseInterface The response interface.
     */
    public function ServiceUnavailable(ResponseInterface $response, string $message = StatusMessageInterface::STATUS_SERVICE_UNAVAILABLE): ResponseInterface
    {
        return $this->withJson($response, $this->getErrorsResponse($message))->withStatus(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);
    }

    /**
     * The get errors response data function.
     * 
     * @param string $message The message to return.
     * 
     * @return array The array contains response data.
     */
    private function getErrorsResponse(string $message): array
    {
        return [
            "errors" => ["message" => $message]
        ];
    }
}

interface StatusMessageInterface
{
    // Client Errors 4xx
    const STATUS_BAD_REQUEST = "The request cannot be fulfilled due to bad syntax.";
    const STATUS_NOT_FOUND = "The requested resource could not be found.";
    const STATUS_CONFLICT = "The request could not be processed because of conflict in the request.";
    const STATUS_PRECONDITION_FAILED = "The server does not meet one of the precondition on the request.";
    const STATUS_UNPROCESSABLE_ENTITY = "The request was unable to be followed due to semantic errors.";
    // Server Errors 5xx
    const STATUS_INTERNAL_SERVER_ERROR = "Unexpected internal server error.";
    const STATUS_SERVICE_UNAVAILABLE = "The server is currently unavailable.";
}
