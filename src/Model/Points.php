<?php

declare(strict_types=1);

namespace App\Model;

class Points
{
    private int $pointPlayer1;
    private int $pointPlayer2;
    private string $deucePointPlayer1;
    private string $deucePointPlayer2;

    public function __construct()
    {
        $this->pointPlayer1 = Point::StartPoint->value();
        $this->pointPlayer2 = Point::StartPoint->value();
        $this->deucePointPlayer1 = Point::DeuceStart->value();
        $this->deucePointPlayer2 = Point::DeuceStart->value();
    }


    public function getPointPlayer1(): int
    {
        return $this->pointPlayer1;
    }

    public function setPointPlayer1(Point $pointPlayer1): void
    {
        $this->pointPlayer1 = $pointPlayer1->value();
    }

    public function getPointPlayer2(): int
    {
        return $this->pointPlayer2;
    }

    public function setPointPlayer2(Point $pointPlayer2): void
    {
        $this->pointPlayer2 = $pointPlayer2->value();
    }

    public function isDeuce(): bool
    {
        return $this->pointPlayer1 === Point::LastPoint->value() && $this->pointPlayer2 === Point::LastPoint->value();
    }

    public function setTieBreakPointPlayer1(): void
    {
        $this->pointPlayer1 += Point::OnePoint->value();
    }

    public function setTieBreakPointPlayer2(): void
    {
        $this->pointPlayer2 += Point::OnePoint->value();
    }

    public function resetPoints(): void
    {
        $this->pointPlayer1 = Point::StartPoint->value();
        $this->pointPlayer2 = Point::StartPoint->value();
    }


    public function getDeucePointPlayer1(): string
    {
        return $this->deucePointPlayer1;
    }

    public function setDeucePointPlayer1(Point $point): void
    {
        $this->deucePointPlayer1 = $point->value();
    }

    public function getDeucePointPlayer2(): string
    {
        return $this->deucePointPlayer2;
    }

    public function setDeucePointPlayer2(Point $point): void
    {
        $this->deucePointPlayer2 = $point->value();
    }

    public function resetDeucePoints(): void
    {
        $this->deucePointPlayer1 = Point::DeuceStart->value();
        $this->deucePointPlayer2 = Point::DeuceStart->value();
    }
}
