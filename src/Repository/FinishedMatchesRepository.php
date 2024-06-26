<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\GameMatch;
use Doctrine\ORM\EntityManager;

class FinishedMatchesRepository
{
    private EntityManager $ORM;

    public function __construct(EntityManager $ORM)
    {
        $this->ORM = $ORM;
    }

    public function add(GameMatch $gameMatch): void
    {
        $this->ORM->persist($gameMatch);
    }

    /**
     * @param string $playerName
     * @param int $countItems
     * @param int $offset
     * @return array<array-key>[]
     */
    public function getMatchesByPlayerName(string $playerName, int $countItems, int $offset = 0): array
    {
        $dql =
            "SELECT pl1.name as playerName1, pl2.name as playerName2, pl3.name as winner " .
            "FROM App\Model\GameMatch as m " .
            "INNER JOIN App\Model\Player as pl1 WITH m.player1 = pl1.id " .
            "INNER JOIN App\Model\Player as pl2 WITH m.player2 = pl2.id " .
            "INNER JOIN App\Model\Player as pl3 WITH m.winner = pl3.id " .
            "WHERE pl1.name LIKE :playerName OR pl2.name LIKE :playerName " .
            "ORDER BY m.id DESC";

        return $this->ORM->createQuery($dql)
            ->setParameter('playerName', "%" . $playerName . "%")
            ->setFirstResult($offset)
            ->setMaxResults($countItems)
            ->getResult();
    }

    /**
     * @param int $countItems
     * @param int $offset
     * @return array<array-key>[]
     */
    public function getAllGameMatchesOnPage(int $countItems, int $offset = 0): array
    {
        $dql =
            "SELECT pl1.name as playerName1, pl2.name as playerName2, pl3.name as winner " .
            "FROM App\Model\GameMatch as m " .
            "INNER JOIN App\Model\Player as pl1 WITH m.player1 = pl1.id " .
            "INNER JOIN App\Model\Player as pl2 WITH m.player2 = pl2.id " .
            "INNER JOIN App\Model\Player as pl3 WITH m.winner = pl3.id " .
            "ORDER BY m.id DESC";

        return $this->ORM->createQuery($dql)
            ->setFirstResult($offset)
            ->setMaxResults($countItems)
            ->getResult();
    }

    public function countAllFinishMatches(): int
    {
        return $this->ORM->getRepository(GameMatch::class)->count([]);
    }

    public function countAllMatchesByPlayerName(string $playerName): int
    {
        $dql =
            "SELECT COUNT(m.id) as count " .
            "FROM App\Model\GameMatch as m " .
            "INNER JOIN App\Model\Player as pl1 WITH m.player1 = pl1.id " .
            "INNER JOIN App\Model\Player as pl2 WITH m.player2 = pl2.id " .
            "INNER JOIN App\Model\Player as pl3 WITH m.winner = pl3.id " .
            "WHERE pl1.name LIKE :playerName OR pl2.name LIKE :playerName ";

        return $this->ORM->createQuery($dql)
            ->setParameter('playerName', "%" . $playerName . "%")
            ->getResult()[0]['count'];
    }
}
