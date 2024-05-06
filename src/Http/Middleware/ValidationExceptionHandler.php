<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exception\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class ValidationExceptionHandler implements MiddlewareInterface
{
    private Twig $renderer;

    public function __construct(Twig $renderer)
    {
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            $response = new Response();
            return $this->renderer->render($response->withStatus($e->getCode()), $e->getTemplatePath(), $e->getArgs());
        }
    }
}