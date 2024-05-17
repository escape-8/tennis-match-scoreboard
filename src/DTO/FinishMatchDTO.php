<?php

declare(strict_types=1);

namespace App\DTO;

class FinishMatchDTO
{
    public readonly string $playerName1;
    public readonly string $playerName2;
    public readonly string $winner;

    public function __construct(string $playerName1, string $playerName2, string $winner)
    {
        $this->playerName1 = $playerName1;
        $this->playerName2 = $playerName2;
        $this->winner = $winner;
    }
}
