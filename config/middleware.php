<?php

declare(strict_types=1);

use App\Http\Middleware\ValidationExceptionHandler;
use Slim\App;

return static function (App $app): void
{
    $app->addErrorMiddleware(false, true, true);
    $app->add(ValidationExceptionHandler::class);
};