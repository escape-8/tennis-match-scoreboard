<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\GameMatch;
use App\Model\GameMatchScore;
use App\Repository\FinishedMatchesRepository;
use App\Repository\PlayersRepository;
use App\Util\Flusher;

class FinishedMatchesPersistenceService
{
    private FinishedMatchesRepository $finishedMatchesRepository;
    private PlayersRepository $playersRepository;
    private Flusher $flusher;

    public function __construct(FinishedMatchesRepository $finishedMatchesRepository, PlayersRepository $playersRepository, Flusher $flusher)
    {
        $this->finishedMatchesRepository = $finishedMatchesRepository;
        $this->playersRepository = $playersRepository;
        $this->flusher = $flusher;
    }

    public function saveMatch(GameMatchScore $gameMatchScore): void
    {
        $players = $this->playersRepository->getPlayersByNames($gameMatchScore->getPlayerName1(), $gameMatchScore->getPlayerName2());

        [$player1, $player2] = $players;

        $winnerId = $player1->getName() === $gameMatchScore->getWinner() ? $player1->getId() : $player2->getId();

        $this->finishedMatchesRepository->add(new GameMatch($player1->getId(), $player2->getId(), $winnerId));
        $this->flusher->flush();
    }
}