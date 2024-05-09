<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\NewMatchPlayersDTO;
use App\Model\GameMatchScore;
use App\Model\Player;
use App\Repository\OngoingMatchesRepository;
use App\Repository\PlayersRepository;
use App\Util\Flusher;

class OngoingMatchesService
{
    private PlayersRepository $playersRepository;
    private Flusher $flusher;
    private OngoingMatchesRepository $ongoingMatches;

    public function __construct(PlayersRepository $playersRepository, Flusher $flusher, OngoingMatchesRepository $ongoingMatchesRepository)
    {
        $this->playersRepository = $playersRepository;
        $this->flusher = $flusher;
        $this->ongoingMatches = $ongoingMatchesRepository;
    }

    public function create(NewMatchPlayersDTO $matchPlayersDTO, string $uuid): void
    {
        foreach ($matchPlayersDTO as $playerName) {
            if (!$this->playersRepository->playerExists($playerName)) {
                $this->playersRepository->add(new Player($playerName));
            }
        }
        $this->flusher->flush();
        $this->ongoingMatches->add($uuid, new GameMatchScore($matchPlayersDTO->playerName1, $matchPlayersDTO->playerName2));
    }

    public function getMatch(string $uuid): GameMatchScore
    {
        return $this->ongoingMatches->get($uuid);
    }

    public function save(GameMatchScore $gameMatchScore, string $uuid): void
    {
        $this->ongoingMatches->add($uuid, $gameMatchScore);
    }
}