<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return static function (App $app): void
{
    $app->get('/', function (Request $request, Response $response, array $args) {
        return $response;
    });
};