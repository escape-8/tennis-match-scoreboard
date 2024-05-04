<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewMatchController;
use Slim\App;

return static function (App $app): void
{
    $app->get('/', [HomeController::class, 'index']);

    $app->get('/new-match', [NewMatchController::class, 'index']);

    $app->post('/new-match', [NewMatchController::class, 'create']);
};