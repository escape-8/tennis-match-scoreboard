<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Service\OngoingMatchesService;
use App\Util\Uuid;
use App\Validator\NewMatchPlayersValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class NewMatchController
{
    private Twig $renderer;
    private NewMatchPlayersValidator $matchPlayersValidator;
    private OngoingMatchesService $ongoingMatchesService;


    public function __construct(Twig $renderer, NewMatchPlayersValidator $matchPlayersValidator, OngoingMatchesService $ongoingMatchesService)
    {
        $this->renderer = $renderer;
        $this->matchPlayersValidator = $matchPlayersValidator;
        $this->ongoingMatchesService = $ongoingMatchesService;
    }

    public function index(Request $request, Response $response): Response
    {
        return $this->renderer->render($response, 'html/pages/new-match.html.twig');
    }

    public function create(Request $request, Response $response): Response
    {
        $uuid = Uuid::uuid4();
        $newMatchDTO = $this->matchPlayersValidator->validate($request->getParsedBody());
        $this->ongoingMatchesService->create($newMatchDTO, $uuid);
        return $response->withHeader('Location', '/match-score?' . http_build_query(['uuid' => $uuid]))->withStatus(302);
    }
}