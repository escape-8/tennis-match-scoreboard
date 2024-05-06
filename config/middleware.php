<?php

declare(strict_types=1);

use App\Http\Middleware\ValidationExceptionHandler;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return static function (App $app): void
{
    $app->add(ValidationExceptionHandler::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(false, true, true);
};