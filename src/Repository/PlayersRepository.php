<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Player;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;

class PlayersRepository
{
    private EntityManager $ORM;

    public function __construct(EntityManager $ORM)
    {
        $this->ORM = $ORM;
    }

    public function add(Player $player): void
    {
        $this->ORM->persist($player);
    }

    public function playerExists(string $name): bool
    {
        $player = $this->ORM->getRepository(Player::class)->findOneBy(['name' => $name]);

        if ($player) {
            return true;
        }

        return false;
    }

    /**
     * @return array<Player>
     * @throws NotSupported
     */
    public function getPlayersByNames(string $namePlayer1, string $namePlayer2): array
    {
        return $this->ORM->getRepository(Player::class)->findBy(['name' => [$namePlayer1, $namePlayer2]]);
    }
}