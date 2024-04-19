<?php

declare(strict_types=1);

use Slim\App;

return static function (App $app): void
{
    $app->addErrorMiddleware(false, true, true);
};