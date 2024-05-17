<?php

declare(strict_types=1);

namespace App\DTO;

class FinishedMatchesDTO
{
    /**
     * @var array<FinishMatchDTO> $finishedMatches
     */
    public readonly array $finishedMatches;
    public readonly string $findPlayerName;
    public readonly int $maxPages;
    public readonly int $page;

    /**
     * @param array<FinishMatchDTO> $finishedMatches
     */
    public function __construct(array $finishedMatches, string $findPlayerName, int $maxPages, int $page = 1)
    {
        $this->finishedMatches = $finishedMatches;
        $this->findPlayerName = $findPlayerName;
        $this->maxPages = $maxPages;
        $this->page = $page;
    }
}
