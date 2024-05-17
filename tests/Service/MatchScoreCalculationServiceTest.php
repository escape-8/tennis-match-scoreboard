<?php

declare(strict_types=1);

namespace Test\Service;

use App\Model\GameMatchScore;
use App\Model\Point;
use App\Service\MatchScoreCalculationService;
use PHPUnit\Framework\TestCase;

class MatchScoreCalculationServiceTest extends TestCase
{
    private MatchScoreCalculationService $calculationService;
    private GameMatchScore $matchScore;

    protected function setUp(): void
    {
        $this->calculationService = new MatchScoreCalculationService();
        $this->matchScore = new GameMatchScore('Mike Riley', 'Jack Row');
    }

    public function testCalcPointsPlayer1(): void
    {
        $winner = 'player1';
        $this->calculationService->updatePoints($this->matchScore, $winner);
        $this->assertEquals(15, $this->matchScore->getPoints()->getPointPlayer1());
    }

    public function testCalcPointsPlayer2(): void
    {
        $winner = 'player2';
        $this->calculationService->updatePoints($this->matchScore, $winner);
        $this->assertEquals(15, $this->matchScore->getPoints()->getPointPlayer2());
    }

    public function testCalcPointTwoPlayers(): void
    {
        $this->calculationService->updatePoints($this->matchScore, 'player1');
        $this->calculationService->updatePoints($this->matchScore, 'player2');
        $this->assertEquals(15, $this->matchScore->getPoints()->getPointPlayer1());
        $this->assertEquals(15, $this->matchScore->getPoints()->getPointPlayer2());
    }

    public function testCalcGamePointsPlayer1(): void
    {
        $winner = 'player1';
        $this->matchScore->getPoints()->setPointPlayer1(Point::FirstPoint);
        $this->matchScore->getPoints()->setPointPlayer1(Point::SecondPoint);
        $this->matchScore->getPoints()->setPointPlayer1(Point::LastPoint);
        $this->calculationService->updatePoints($this->matchScore, $winner);
        $this->assertEquals(1, $this->matchScore->getGames()->getGamePlayer1());
    }

    public function testCalcGamePointsPlayer2(): void
    {
        $winner = 'player2';
        $this->matchScore->getPoints()->setPointPlayer2(Point::LastPoint);
        $this->calculationService->updatePoints($this->matchScore, $winner);
        $this->assertEquals(1, $this->matchScore->getGames()->getGamePlayer2());
    }

    public function testResetPointsPlayersAfterWinGame(): void
    {
        $winner = 'player2';
        $this->matchScore->getPoints()->setPointPlayer2(Point::LastPoint);
        $this->calculationService->updatePoints($this->matchScore, $winner);
        $this->assertEquals(0, $this->matchScore->getPoints()->getPointPlayer1());
        $this->assertEquals(0, $this->matchScore->getPoints()->getPointPlayer2());
    }

    public function testAdvantagePlayer1(): void
    {
        $winner = 'player1';
        $this->calculationService->updateDeucePoints($this->matchScore, $winner);
        $this->assertEquals('AD', $this->matchScore->getPoints()->getDeucePointPlayer1());
        $this->assertEquals('-', $this->matchScore->getPoints()->getDeucePointPlayer2());
    }

    public function testAdvantagePlayer2(): void
    {
        $winner = 'player2';
        $this->calculationService->updateDeucePoints($this->matchScore, $winner);
        $this->assertEquals('-', $this->matchScore->getPoints()->getDeucePointPlayer1());
        $this->assertEquals('AD', $this->matchScore->getPoints()->getDeucePointPlayer2());
    }

    public function testWinDeucePlayer1(): void
    {
        $winner = 'player1';
        $this->matchScore->getPoints()->setDeucePointPlayer1(Point::DeuceAdvantage);
        $this->matchScore->getPoints()->setDeucePointPlayer2(Point::DeuceNoAdvantage);
        $this->calculationService->updateDeucePoints($this->matchScore, $winner);
        $this->assertEquals(0, $this->matchScore->getPoints()->getPointPlayer1());
        $this->assertEquals(0, $this->matchScore->getPoints()->getPointPlayer2());
        $this->assertEquals(1, $this->matchScore->getGames()->getGamePlayer1());
        $this->assertEquals(0, $this->matchScore->getGames()->getGamePlayer2());
    }

    public function testChangeAdvantagePlayer1ToNoAdvantage(): void
    {
        $winner = 'player2';
        $this->matchScore->getPoints()->setDeucePointPlayer1(Point::DeuceAdvantage);
        $this->matchScore->getPoints()->setDeucePointPlayer2(Point::DeuceNoAdvantage);
        $this->calculationService->updateDeucePoints($this->matchScore, $winner);
        $this->assertEquals('40', $this->matchScore->getPoints()->getDeucePointPlayer1());
        $this->assertEquals('40', $this->matchScore->getPoints()->getDeucePointPlayer2());
    }

    public function testStartTieBreakPoints(): void
    {
        for ($point = 1; $point <= Point::TieBreakNeedPoints->value(); $point++) {
            $this->matchScore->getGames()->setGamePlayer1();
            $this->matchScore->getGames()->setGamePlayer2();
        }
        $this->assertEquals(0, $this->matchScore->getPoints()->getPointPlayer1());
        $this->assertEquals(0, $this->matchScore->getPoints()->getPointPlayer2());
    }

    public function testUpdateTieBreakPoints(): void
    {
        $winner = 'player1';
        $this->calculationService->updateTieBreakPoints($this->matchScore, $winner);
        $this->assertEquals(1, $this->matchScore->getPoints()->getPointPlayer1());
        $this->assertEquals(0, $this->matchScore->getPoints()->getPointPlayer2());
    }

    public function testHaveTieBreakPointsAdvantage(): void
    {
        $winner = 'player1';
        for ($point = 1; $point <= Point::TieBreakNeedPoints->value(); $point++) {
            $this->calculationService->updateTieBreakPoints($this->matchScore, $winner);
        }
        $this->assertEquals(1, $this->matchScore->getSets()->getSetPlayer1());
    }

    public function testNotHaveTieBreakPointsAdvantage(): void
    {
        $winner = 'player1';
        for ($point = 2; $point <= Point::TieBreakNeedPoints->value(); $point++) {
            $this->calculationService->updateTieBreakPoints($this->matchScore, $winner);
        }
        $this->assertEquals(0, $this->matchScore->getSets()->getSetPlayer1());
    }

    public function testTieBreakPointsAdvantageMoreThanNeed(): void
    {
        $winner = 'player1';
        for ($point = 0; $point <= Point::TieBreakNeedPoints->value(); $point++) {
            $this->calculationService->updateTieBreakPoints($this->matchScore, $winner);
        }
        $this->assertEquals(1, $this->matchScore->getSets()->getSetPlayer1());
    }

    public function testUpdateSetWhenSixGames(): void
    {
        $winner = 'player1';
        for ($point = 1; $point <= Point::MinNeedGamesForSet->value(); $point++) {
            $this->calculationService->updateGame($this->matchScore, $winner);
        }
        $this->assertEquals(1, $this->matchScore->getSets()->getSetPlayer1());
    }

    public function testFinishMatch(): void
    {
        $winner = 'player1';
        for ($point = 1; $point <= Point::AdvantagePoints->value(); $point++) {
            $this->calculationService->updateSet($this->matchScore, $winner);
        }
        $this->assertTrue($this->matchScore->matchIsOver());
    }

    public function testMatchScoreCalculate(): void
    {
        $this->calculationService->matchScoreCalculate($this->matchScore, 'player1');
        $this->calculationService->matchScoreCalculate($this->matchScore, 'player2');
        $this->assertEquals(15, $this->matchScore->getPoints()->getPointPlayer1());
        $this->assertEquals(15, $this->matchScore->getPoints()->getPointPlayer2());
    }
}
