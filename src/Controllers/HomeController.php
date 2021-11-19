<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Supports\Responders\ApiResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Home Controller.
 */
final class HomeController
{
    /**
     * @var ApiResponder The generic api responder.
     */
    private ApiResponder $responder;

    /**
     * @param ApiResponder $responder The generic api responder.
     */
    public function __construct(ApiResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @param Request $request The request.
     * @param Response $response The response.
     * 
     * @return Response The response.
     */
    public function index(Request $request, Response $response): Response
    {
        return $this->responder->withRedirectFor($response, 'docs');
    }
}
