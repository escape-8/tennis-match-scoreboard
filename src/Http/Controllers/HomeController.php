<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class HomeController
{
    private PhpRenderer $renderer;

    public function __construct(PhpRenderer $renderer)
    {
        $this->renderer = $renderer;
    }
    public function index(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'index.phtml');
    }
}