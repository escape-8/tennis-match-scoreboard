<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class HomeController
{
    private Twig $renderer;

    public function __construct(Twig $renderer)
    {
        $this->renderer = $renderer;
    }
    public function index(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'index.html.twig');
    }
}