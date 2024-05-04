<?php

declare(strict_types=1);

namespace App\Util;

use Doctrine\ORM\EntityManager;

class Flusher
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}