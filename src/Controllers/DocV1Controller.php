<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Supports\Responders\ApiResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Doc v1 Controller.
 */
final class DocV1Controller
{
    /**
     * @var ApiResponder The generic api responder.
     */
    private ApiResponder $responder;

    /**
     * The constructor.
     * 
     * @param ApiResponder $responder The generic api responder.
     */
    public function __construct(ApiResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * The index.
     * 
     * @param Request $request The request.
     * @param Response $response The response.
     * 
     * @return Response The response.
     */
    public function index(Request $request, Response $response): Response
    {
        $docV1 = __DIR__ . '/../../resources/api/docV1.json';

        if (!file_exists($docV1)) {
            return $this->responder->NotFound($response);
        }

        $contents = json_decode(file_get_contents($docV1));

        // Inject the doc contents with environment variable here

        return $this->responder->withTemplate($response, 'doc/swagger.php', ['spec' => json_encode($contents)]);
    }
}
