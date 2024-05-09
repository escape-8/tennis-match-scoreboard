<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'matches')]
class GameMatch
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;
    #[ORM\Column(type: 'integer')]
    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(referencedColumnName : 'id')]
    private int $player1;
    #[ORM\Column(type: 'integer')]
    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(referencedColumnName : 'id')]
    private int $player2;
    #[ORM\Column(type: 'integer')]
    private int $winner;

    public function __construct(int $idPlayer1, int $idPlayer2, int $winner)
    {
        $this->player1 = $idPlayer1;
        $this->player2 = $idPlayer2;
        $this->winner = $winner;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer1(): int
    {
        return $this->player1;
    }

    public function getPlayer2(): int
    {
        return $this->player2;
    }

    public function getWinner(): int
    {
        return $this->winner;
    }
}