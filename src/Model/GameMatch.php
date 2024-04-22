<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'matches')]
class GameMatch
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;
    #[ORM\Column(type: 'string')]
    private string $uuid;
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
    #[ORM\Column(type: 'string')]
    private string $score;

    public function __construct(int $id, string $uuid, int $idPlayer1, int $idPlayer2, int $winner, string $score)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->player1 = $idPlayer1;
        $this->player2 = $idPlayer2;
        $this->winner = $winner;
        $this->score = $score;
    }

}