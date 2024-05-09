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
}