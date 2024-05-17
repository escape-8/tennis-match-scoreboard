<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Service\FinishedMatchService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class MatchesController
{
    private Twig $renderer;
    private FinishedMatchService $finishedMatchService;

    public function __construct(FinishedMatchService $findMatchService, Twig $renderer)
    {
        $this->finishedMatchService = $findMatchService;
        $this->renderer = $renderer;
    }

    public function index(Request $request, Response $response): Response
    {
        $name = $request->getQueryParams()['filter_by_name'] ?? $request->getQueryParams()['playerName'];
        $page = (int) $request->getQueryParams()['page'] <= 0 ? 1 : (int) $request->getQueryParams()['page'];

        if ($name) {
            $dto = $this->finishedMatchService->getPageFindMatchesByPlayerName($name, $page);
            return $this->renderer->render(
                $response,
                'html/pages/matches.html.twig',
                ['matches' => $dto]
            );
        }

        $dto = $this->finishedMatchService->getMatchesPage($page);

        return $this->renderer->render(
            $response,
            'html/pages/matches.html.twig',
            ['matches' => $dto]
        );
    }
}
