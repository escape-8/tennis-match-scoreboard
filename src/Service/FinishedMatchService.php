<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\FinishedMatchesDTO;
use App\DTO\FinishMatchDTO;
use App\Repository\FinishedMatchesRepository;
use App\Util\Paginator;

class FinishedMatchService
{
    private FinishedMatchesRepository $finishedMatchesRepo;
    private Paginator $paginator;

    public function __construct(FinishedMatchesRepository $finishedMatchesRepo, Paginator $pager)
    {
        $this->finishedMatchesRepo = $finishedMatchesRepo;
        $this->paginator = $pager;
    }

    public function getPageFindMatchesByPlayerName(string $playerName, int $page): FinishedMatchesDTO
    {
        $offset = $this->paginator->calcOffset($page);
        $maxPages = $this->paginator->calcTotalPages($this->finishedMatchesRepo->countAllMatchesByPlayerName($playerName));
        $matches = $this->finishedMatchesRepo->getMatchesByPlayerName($playerName, $this->paginator->getItemsPerPage(), $offset);
        $arrayFinishMatches = $this->packageToDTO($matches);

        return new FinishedMatchesDTO($arrayFinishMatches, $playerName, $maxPages, $page);
    }

    public function getMatchesPage(int $page): FinishedMatchesDTO
    {
        $offset = $this->paginator->calcOffset($page);
        $maxPages = $this->paginator->calcTotalPages($this->finishedMatchesRepo->countAllFinishMatches());
        $matches = $this->finishedMatchesRepo->getAllGameMatchesOnPage($this->paginator->getItemsPerPage(), $offset);
        $arrayFinishMatches = $this->packageToDTO($matches);

        return new FinishedMatchesDTO($arrayFinishMatches, '', $maxPages, $page);
    }

    /**
     * @return array<FinishMatchDTO>
     */
    private function packageToDTO(array $matches): array
    {
        $arrayFinishMatches = [];
        foreach ($matches as $match) {
            $arrayFinishMatches[] = new FinishMatchDTO($match['playerName1'], $match['playerName2'], $match['winner']);
        }

        return $arrayFinishMatches;
    }
}