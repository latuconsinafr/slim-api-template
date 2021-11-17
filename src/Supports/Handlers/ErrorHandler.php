<?php

declare(strict_types=1);

namespace App\Supports\Handlers;

use App\Supports\Loggers\Logger;
use App\Supports\Responders\ApiResponder;
use App\Supports\Responders\StatusMessageInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Slim\Interfaces\ErrorHandlerInterface;
use DomainException;
use InvalidArgumentException;
use Throwable;

/**
 * Error Renderer.
 */
final class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var ApiResponder The responder
     */
    private ApiResponder $responder;

    /**
     * @var ResponseFactoryInterface The response factory
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var LoggerInterface The logger interface
     */
    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param ApiResponder $responder The responder
     * @param ResponseFactoryInterface $responseFactory The response factory
     * @param Logger $logger The logger
     */
    public function __construct(
        ApiResponder $responder,
        ResponseFactoryInterface $responseFactory,
        Logger $logger
    ) {
        $this->responder = $responder;
        $this->responseFactory = $responseFactory;
        $this->logger = $logger
            ->addFileHandler('error.log')
            ->addConsoleHandler()
            ->createLogger();
    }

    /**
     * Invoke.
     *
     * @param ServerRequestInterface $request The request
     * @param Throwable $exception The exception
     * @param bool $displayErrorDetails Show error details
     * @param bool $logErrors Log errors
     * @param bool $logErrorDetails Log error details
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        
        // Log errors
        if ($logErrors) {
            $error = $this->getErrorDetails($exception, $logErrorDetails);
            $error['method'] = (string)$request->getMethod();
            $error['url'] = (string)$request->getUri();

            $this->logger->error($exception->getMessage(), $error);
        }

        // Render response
        $statusCode = $this->getHttpStatusCode($exception);
        $response = $this->responseFactory->createResponse();
        $response = $this->responder->withJson($response, [
            'errors' => $this->getErrorDetails($exception, $displayErrorDetails, $statusCode),
        ]);

        return $response->withStatus($statusCode);
    }

    /**
     * Get http status code.
     *
     * @param Throwable $exception The exception.
     *
     * @return int The http code.
     */
    private function getHttpStatusCode(Throwable $exception): int
    {
        // Default would be service unavailable
        $statusCode = StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE;

        // Get original http status code
        if ($exception instanceof HttpException) {
            $statusCode = (int)$exception->getCode();
        }

        // Bad request
        if ($exception instanceof DomainException || $exception instanceof InvalidArgumentException) {
            $statusCode = StatusCodeInterface::STATUS_BAD_REQUEST;
        }

        // Not found
        $file = basename($exception->getFile());
        if ($file === 'CallableResolver.php') {
            $statusCode = StatusCodeInterface::STATUS_NOT_FOUND;
        }

        return $statusCode;
    }

    /**
     * Get error message.
     *
     * @param Throwable $exception The error.
     * @param bool $displayErrorDetails Display details.
     * @param int|null $statusCode The http status code.
     * 
     * @return array The error message and details, if unsuppressed.
     */
    private function getErrorDetails(Throwable $exception, bool $displayErrorDetails, int $statusCode = null): array
    {
        // Display error details
        if ($displayErrorDetails === true) {
            return [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'previous' => $exception->getPrevious(),
                'trace' => $exception->getTrace(),
            ];
        }

        // Suppressed error details, the default would be service unavailable
        $message = StatusMessageInterface::STATUS_SERVICE_UNAVAILABLE;

        switch ($statusCode) {
            case StatusCodeInterface::STATUS_BAD_REQUEST:
                $message = StatusMessageInterface::STATUS_BAD_REQUEST;
                break;

            case StatusCodeInterface::STATUS_NOT_FOUND:
                $message = StatusMessageInterface::STATUS_NOT_FOUND;
                break;

            case StatusCodeInterface::STATUS_CONFLICT:
                $message = StatusMessageInterface::STATUS_CONFLICT;
                break;

            case StatusCodeInterface::STATUS_PRECONDITION_FAILED:
                $message = StatusMessageInterface::STATUS_PRECONDITION_FAILED;
                break;

            case StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY:
                $message = StatusMessageInterface::STATUS_UNPROCESSABLE_ENTITY;
                break;

            case StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR:
                $message = StatusMessageInterface::STATUS_INTERNAL_SERVER_ERROR;
                break;
            default:
                break;
        }

        return [
            'message' => $message,
        ];
    }
}
