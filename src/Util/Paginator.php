<?php

declare(strict_types=1);

namespace App\Util;

class Paginator
{
    private int $itemsPerPage = 7;

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function calcOffset(int $numPage): int
    {
        return abs(($numPage - 1) * $this->itemsPerPage);
    }

    public function calcTotalPages(int $totalRecords): int
    {
        return (int) ceil(($totalRecords / $this->itemsPerPage));
    }
}
