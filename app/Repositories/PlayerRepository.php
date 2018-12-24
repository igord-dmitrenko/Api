<?php

namespace App\Repositories;

use App\Models\Player;

class PlayerRepository extends Repository
{
    /**
     * PlayerRepository constructor.
     *
     * @param Player $player
     */
    public function __construct(Player $player)
    {
        parent::__construct($player);
    }
}
