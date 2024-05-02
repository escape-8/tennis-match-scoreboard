<?php

declare(strict_types=1);

namespace App\DTO;

class NewMatchPlayersDTO
{
    public readonly string $playerName1;
    public readonly string $playerName2;

    public function __construct(string $playerName1, string $playerName2)
    {
        $this->playerName1 = $playerName1;
        $this->playerName2 = $playerName2;
    }

}