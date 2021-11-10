<?php

namespace App\Controllers;

use App\Supports\Responder\Responder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Yaml\Yaml;

/**
 * Doc v1 Controller.
 */
final class Docv1Controller
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
        $docv1 = __DIR__ . '/../../resources/api/docv1.yaml';

        $viewData = [
            'spec' => json_encode(Yaml::parseFile($docv1)),
        ];

        return $this->responder->withTemplate($response, 'doc/swagger.php', $viewData);
    }
}
