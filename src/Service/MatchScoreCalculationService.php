<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\GameMatchScore;
use App\Model\Games;
use App\Model\Point;
use App\Model\Points;
use App\Model\Sets;

class MatchScoreCalculationService
{
    private array $playerNames;
    private array $pointsPlayers;
    private array $deucePointsPlayers;
    private array $gamesPlayers;
    private array $setsPlayers;
    private array $setPoint;
    private array $setDeucePoint;
    private array $setTieBreakPoint;
    private array $setGame;
    private array $addSet;

    public function __construct()
    {
        $this->playerNames = [
            'player1' => fn(GameMatchScore $gameMatchScore): string => $gameMatchScore->getPlayerName1(),
            'player2' => fn(GameMatchScore $gameMatchScore): string => $gameMatchScore->getPlayerName2(),
        ];

        $this->pointsPlayers = [
            'player1' => fn(GameMatchScore $gameMatchScore): int => $gameMatchScore->getPoints()->getPointPlayer1(),
            'player2' => fn(GameMatchScore $gameMatchScore): int => $gameMatchScore->getPoints()->getPointPlayer2(),
        ];

        $this->deucePointsPlayers = [
            'player1' => fn(GameMatchScore $gameMatchScore): string => $gameMatchScore->getPoints()->getDeucePointPlayer1(),
            'player2' => fn(GameMatchScore $gameMatchScore): string => $gameMatchScore->getPoints()->getDeucePointPlayer2(),
        ];

        $this->gamesPlayers = [
            'player1' => fn(GameMatchScore $gameMatchScore): int => $gameMatchScore->getGames()->getGamePlayer1(),
            'player2' => fn(GameMatchScore $gameMatchScore): int => $gameMatchScore->getGames()->getGamePlayer2(),
        ];

        $this->setsPlayers = [
            'player1' => fn(GameMatchScore $gameMatchScore): int => $gameMatchScore->getSets()->getSetPlayer1(),
            'player2' => fn(GameMatchScore $gameMatchScore): int => $gameMatchScore->getSets()->getSetPlayer2(),
        ];

        $this->setPoint = [
            'player1' => fn(Points $point, Point $numPoint) => $point->setPointPlayer1($numPoint),
            'player2' => fn(Points $point, Point $numPoint) => $point->setPointPlayer2($numPoint),
        ];

        $this->setDeucePoint = [
            'player1' => fn(Points $point, Point $numPoint) => $point->setDeucePointPlayer1($numPoint),
            'player2' => fn(Points $point, Point $numPoint) => $point->setDeucePointPlayer2($numPoint),
        ];

        $this->setTieBreakPoint = [
            'player1' => fn(Points $point) => $point->setTieBreakPointPlayer1(),
            'player2' => fn(Points $point) => $point->setTieBreakPointPlayer2(),
        ];

        $this->setGame = [
            'player1' => fn(Games $games) => $games->setGamePlayer1(),
            'player2' => fn(Games $games) => $games->setGamePlayer2(),
        ];

        $this->addSet = [
            'player1' => fn(Sets $sets) => $sets->addSetPlayer1(),
            'player2' => fn(Sets $sets) => $sets->addSetPlayer2(),
        ];
    }


    public function matchScoreCalculate(GameMatchScore $gameMatchScore, string $winner): GameMatchScore
    {

        if ($gameMatchScore->getPoints()->isDeuce()) {
            $this->updateDeucePoints($gameMatchScore, $winner);
            return $gameMatchScore;
        }

        if ($gameMatchScore->getGames()->isTieBreak()) {
            $this->updateTieBreakPoints($gameMatchScore, $winner);
            return $gameMatchScore;
        }

        $this->updatePoints($gameMatchScore, $winner);

        return $gameMatchScore;
    }

    public function updatePoints(GameMatchScore $gameMatchScore, string $winner): void
    {
        $winnerPoint = $this->pointsPlayers[$winner]($gameMatchScore);

        $numPoint = null;

        switch ($winnerPoint) {
            case Point::StartPoint->value():
                $numPoint = Point::FirstPoint;
                break;
            case Point::FirstPoint->value():
                $numPoint = Point::SecondPoint;
                break;
            case Point::SecondPoint->value():
                $numPoint = Point::LastPoint;
                break;
            case Point::LastPoint->value():
                $gameMatchScore->getPoints()->resetPoints();
                $this->updateGame($gameMatchScore, $winner);
                return;
        }

        $this->setPoint[$winner]($gameMatchScore->getPoints(), $numPoint);
    }

    public function updateGame(GameMatchScore $gameMatchScore, string $winner): void
    {
        $this->setGame[$winner]($gameMatchScore->getGames());

        if ($this->gamesPlayers[$winner]($gameMatchScore) >= Point::MinNeedGamesForSet->value() &&
            $this->haveGamesAdvantage($gameMatchScore->getGames())
        ) {
            $this->updateSet($gameMatchScore, $winner);
            $gameMatchScore->getGames()->resetGames();
        }
    }

    public function updateDeucePoints(GameMatchScore $gameMatchScore, string $winner): void
    {
        if (($this->deucePointsPlayers['player1']($gameMatchScore) === Point::DeuceAdvantage->value() && $winner === 'player2') ||
            ($this->deucePointsPlayers['player2']($gameMatchScore) === Point::DeuceAdvantage->value() && $winner === 'player1')
        ) {
            $gameMatchScore->getPoints()->resetDeucePoints();
            return;
        }

        $winnerPoint = $this->deucePointsPlayers[$winner]($gameMatchScore);

        switch ($winnerPoint) {
            case Point::DeuceStart->value():
                foreach ($this->setDeucePoint as $playerHandler) {
                    $playerHandler($gameMatchScore->getPoints(), Point::DeuceNoAdvantage);
                }
                $this->setDeucePoint[$winner]($gameMatchScore->getPoints(), Point::DeuceAdvantage);
                break;
            case Point::DeuceAdvantage->value():
                $gameMatchScore->getPoints()->resetPoints();
                $this->updateGame($gameMatchScore, $winner);
                break;
        }
    }

    public function updateTieBreakPoints(GameMatchScore $gameMatchScore, string $winner): void
    {
        $this->setTieBreakPoint[$winner]($gameMatchScore->getPoints());

        if ($this->pointsPlayers[$winner]($gameMatchScore) >= Point::TieBreakNeedPoints->value() &&
            $this->haveTieBreakPointsAdvantage($gameMatchScore->getPoints())
        ) {
            $this->updateSet($gameMatchScore, $winner);
            $gameMatchScore->getGames()->resetGames();
            $gameMatchScore->getPoints()->resetPoints();
        }
    }

    public function updateSet(GameMatchScore $gameMatchScore, string $winner): void
    {
        $this->addSet[$winner]($gameMatchScore->getSets());

        if ($this->setsPlayers[$winner]($gameMatchScore) === Point::AdvantagePoints->value()) {
            $gameMatchScore->setMatchIsOver(true);
            $gameMatchScore->setWinner($this->playerNames[$winner]($gameMatchScore));
        }
    }

    private function haveGamesAdvantage(Games $games): bool
    {
        return abs($games->getGamePlayer1() - $games->getGamePlayer2()) >= Point::AdvantagePoints->value();
    }
    private function haveTieBreakPointsAdvantage(Points $points): bool
    {
        return abs($points->getPointPlayer1() - $points->getPointPlayer2()) >= Point::AdvantagePoints->value();
    }
}