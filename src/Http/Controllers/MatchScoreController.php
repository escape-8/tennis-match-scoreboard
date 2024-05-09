<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\FinishMatchDTO;
use App\DTO\GameMatchScoreDTO;
use App\Service\FinishedMatchesPersistenceService;
use App\Service\MatchScoreCalculationService;
use App\Service\OngoingMatchesService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class MatchScoreController
{
    private Twig $renderer;
    private OngoingMatchesService $ongoingMatchesService;
    private MatchScoreCalculationService $matchScoreCalculationService;
    private FinishedMatchesPersistenceService $finishedMatchesPersistenceService;


    public function __construct(Twig $renderer, OngoingMatchesService $ongoingMatchesService, MatchScoreCalculationService $matchScoreCalculationService, FinishedMatchesPersistenceService $finishedMatchesPersistenceService)
    {
        $this->renderer = $renderer;
        $this->ongoingMatchesService = $ongoingMatchesService;
        $this->matchScoreCalculationService = $matchScoreCalculationService;
        $this->finishedMatchesPersistenceService = $finishedMatchesPersistenceService;

    }


    public function show(Request $request, Response $response): Response
    {
        $uuid = $request->getQueryParams()['uuid'];
        $match = $this->ongoingMatchesService->getMatch($uuid);
        return  $this->renderer->render(
            $response,
            'html/pages/match-score.html.twig',
            ['match' => new GameMatchScoreDTO(
                $uuid,
                $match->getPlayerName1(),
                $match->getPlayerName2(),
                $match->getPoints(),
                $match->getGames(),
                $match->getSets(),
                )
            ]);
    }

    public function update(Request $request, Response $response): Response
    {
        $uuid = $request->getQueryParams()['uuid'];
        $winner = $request->getParsedBody()['winner'];

        $match = $this->ongoingMatchesService->getMatch($uuid);
        $updateMatch = $this->matchScoreCalculationService->matchScoreCalculate($match, $winner);

        if ($updateMatch->matchIsOver()) {
            $this->finishedMatchesPersistenceService->saveMatch($updateMatch);

            return $this->renderer->render(
                $response,
                'html/pages/finish-match.html.twig',
                ['match' => new FinishMatchDTO(
                    $updateMatch->getWinner(),
                    $updateMatch->getPlayerName1(),
                    $updateMatch->getPlayerName2(),
                    $updateMatch->getSets(),
                    )
                ]);
        }

        $this->ongoingMatchesService->save($updateMatch, $uuid);

        return  $this->renderer->render(
            $response,
            'html/pages/match-score.html.twig',
            ['match' => new GameMatchScoreDTO(
                $uuid,
                $updateMatch->getPlayerName1(),
                $updateMatch->getPlayerName2(),
                $updateMatch->getPoints(),
                $updateMatch->getGames(),
                $updateMatch->getSets(),
                )
            ]);
    }
}