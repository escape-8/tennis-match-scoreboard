<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\GameMatchScore;
use Redis;

class OngoingMatchesRepository
{
    private Redis $repository;

    public function __construct(Redis $repository)
    {
        $this->repository = $repository;
    }

    public function add(string $uuid, GameMatchScore $gameMatchScore): void
    {
        $this->repository->setex($uuid, 1800, serialize($gameMatchScore));
    }

    public function get(string $uuid): GameMatchScore
    {
        return unserialize($this->repository->get($uuid));
    }

    public function remove(string $uuid): void
    {
        $this->repository->del($uuid);
    }
}