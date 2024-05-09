<?php

declare(strict_types=1);

namespace App\Model;

class GameMatchScore
{
    private string $playerName1;
    private string $playerName2;
    private Points $points;
    private Games $games;
    private Sets $sets;
    private bool $isOver;
    private ?string $winner;

    public function __construct(string $playerName1, string $playerName2)
    {
        $this->playerName1 = $playerName1;
        $this->playerName2 = $playerName2;
        $this->points = new Points();
        $this->games = new Games();
        $this->sets = new Sets();
        $this->isOver = false;
        $this->winner = null;
    }

    public function getPlayerName1(): string
    {
        return $this->playerName1;
    }

    public function getPlayerName2(): string
    {
        return $this->playerName2;
    }

    public function getPoints(): Points
    {
        return $this->points;
    }

    public function getGames(): Games
    {
        return $this->games;
    }

    public function getSets(): Sets
    {
        return $this->sets;
    }

    public function matchIsOver(): bool
    {
        return $this->isOver;
    }

    public function setMatchIsOver(bool $isOver): void
    {
        $this->isOver = $isOver;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): void
    {
        $this->winner = $winner;
    }
}