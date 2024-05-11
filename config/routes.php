<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\MatchScoreController;
use App\Http\Controllers\NewMatchController;
use Slim\App;

return static function (App $app): void
{
    $app->get('/', [HomeController::class, 'index'])->setName('home');

    $app->get('/new-match', [NewMatchController::class, 'index'])->setName('new-match');

    $app->post('/new-match', [NewMatchController::class, 'create'])->setName('create-new-match');

    $app->get('/match-score', [MatchScoreController::class, 'show'])->setName('match-score');

    $app->post('/match-score', [MatchScoreController::class, 'update'])->setName('update-match-score');

    $app->get('/matches', [MatchesController::class, 'index'])->setName('matches');
};