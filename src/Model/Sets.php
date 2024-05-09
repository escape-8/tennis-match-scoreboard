<?php

declare(strict_types=1);

namespace App\Model;

class Sets
{
    private int $setPlayer1;
    private int $setPlayer2;


    public function __construct()
    {
        $this->setPlayer1 = Point::StartPoint->value();
        $this->setPlayer2 = Point::StartPoint->value();
    }

    public function getSetPlayer1(): int
    {
        return $this->setPlayer1;
    }

    public function addSetPlayer1(): void
    {
        $this->setPlayer1 += Point::OnePoint->value();
    }

    public function getSetPlayer2(): int
    {
        return $this->setPlayer2;
    }

    public function addSetPlayer2(): void
    {
        $this->setPlayer2 += Point::OnePoint->value();
    }
}