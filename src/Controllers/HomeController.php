<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Supports\Responders\Responder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Home Controller.
 */
final class HomeController
{
    /**
     * @var Responder The generic responder
     */
    private Responder $responder;

    /**
     * The constructor.
     */
    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * The index.
     * 
     * @param Request $request The request.
     * @param Response $response The response.
     * @param array $args The query parameters.
     * 
     * @return Response
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        return $this->responder->withRedirectFor($response, 'docs');
    }
}
