<?php

declare(strict_types=1);

namespace App\Model;

class Games
{
    private int $gamePlayer1;
    private int $gamePlayer2;

    public function __construct()
    {
        $this->gamePlayer1 = Point::StartPoint->value();
        $this->gamePlayer2 = Point::StartPoint->value();
    }

    public function getGamePlayer1(): int
    {
        return $this->gamePlayer1;
    }

    public function setGamePlayer1(): void
    {
        $this->gamePlayer1 += Point::OnePoint->value();
    }

    public function getGamePlayer2(): int
    {
        return $this->gamePlayer2;
    }

    public function setGamePlayer2(): void
    {
        $this->gamePlayer2 += Point::OnePoint->value();
    }

    public function resetGames(): void
    {
        $this->gamePlayer1 = Point::StartPoint->value();
        $this->gamePlayer2 = Point::StartPoint->value();
    }

    public function isTieBreak(): bool
    {
        return $this->gamePlayer1 === Point::MinNeedGamesForSet->value() &&
               $this->gamePlayer2 === Point::MinNeedGamesForSet->value();
    }
}
