<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Games;
use App\Model\Points;
use App\Model\Sets;

class GameMatchScoreDTO
{
    public readonly string $uuid;
    public readonly string $playerName1;
    public readonly string $playerName2;
    public readonly int $pointPlayer1;
    public readonly int $pointPlayer2;
    public readonly string $deucePointPlayer1;
    public readonly string $deucePointPlayer2;
    public readonly int $gamePlayer1;
    public readonly int $gamePlayer2;
    public readonly int $setPlayer1;
    public readonly int $setPlayer2;
    public readonly bool $isDeuce;
    public readonly bool $isTieBreak;

    public function __construct(string $uuid,
                                string $playerName1,
                                string$playerName2,
                                Points $points,
                                Games $games,
                                Sets $sets)
    {
        $this->uuid = $uuid;
        $this->playerName1 = $playerName1;
        $this->playerName2 = $playerName2;
        $this->pointPlayer1 = $points->getPointPlayer1();
        $this->pointPlayer2 = $points->getPointPlayer2();
        $this->deucePointPlayer1 = $points->getDeucePointPlayer1();
        $this->deucePointPlayer2 = $points->getDeucePointPlayer2();
        $this->gamePlayer1 = $games->getGamePlayer1();
        $this->gamePlayer2 = $games->getGamePlayer2();
        $this->setPlayer1 = $sets->getSetPlayer1();
        $this->setPlayer2 = $sets->getSetPlayer2();
        $this->isDeuce = $points->isDeuce();
        $this->isTieBreak = $games->isTieBreak();
    }
}