<?php

declare(strict_types=1);

use App\Http\Middleware\ValidationExceptionHandler;
use Slim\App;

return static function (App $app): void
{
    $app->add(ValidationExceptionHandler::class);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(false, true, true);
};