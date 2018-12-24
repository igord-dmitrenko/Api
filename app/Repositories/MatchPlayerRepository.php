<?php

namespace App\Repositories;

use App\Models\MatchPlayer;

class MatchPlayerRepository extends Repository
{
    /**
     * MatchPlayerRepository constructor.
     *
     * @param MatchPlayer $matchPlayer
     */
    public function __construct(MatchPlayer $matchPlayer)
    {
        parent::__construct($matchPlayer);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getCountByPlayerId($id)
    {
        return $this->getModel()->where(['player_id' => $id])->get()->count();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getPlayersByMatchId($id)
    {
        return $this->getModel()->where(['match_id' => $id])->get();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getMatchesIdsPlayerId($id)
    {
        return $this->getModel()->where(['player_id' => $id])->get()->pluck('match_id')->toArray();
    }
}
