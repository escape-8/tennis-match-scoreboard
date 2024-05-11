<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Sets;

class FinishGameMatchDTO
{
    public readonly string $winner;
    public readonly string $playerName1;
    public readonly string $playerName2;
    public readonly int $setPlayer1;
    public readonly int $setPlayer2;

    public function __construct(string $winner, string $playerName1, string $playerName2, Sets $sets)
    {
        $this->winner = $winner;
        $this->playerName1 = $playerName1;
        $this->playerName2 = $playerName2;
        $this->setPlayer1 = $sets->getSetPlayer1();
        $this->setPlayer2 = $sets->getSetPlayer2();
    }


}