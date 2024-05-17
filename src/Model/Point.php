<?php

namespace App\Model;

enum Point
{
    case StartPoint;
    case FirstPoint;
    case SecondPoint;
    case LastPoint;
    case OnePoint;
    case DeuceStart;
    case DeuceAdvantage;
    case DeuceNoAdvantage;
    case MinNeedGamesForSet;
    case TieBreakNeedPoints;
    case AdvantagePoints;

    public function value(): int|string
    {
        return match ($this) {
            Point::StartPoint => 0,
            Point::FirstPoint => 15,
            Point::SecondPoint => 30,
            Point::LastPoint => 40,
            Point::OnePoint => 1,
            Point::DeuceStart => '40',
            Point::DeuceAdvantage => 'AD',
            Point::DeuceNoAdvantage => '-',
            Point::MinNeedGamesForSet => 6,
            Point::TieBreakNeedPoints => 7,
            Point::AdvantagePoints => 2,
        };
    }
}
