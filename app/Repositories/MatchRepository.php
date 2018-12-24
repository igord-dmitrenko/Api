<?php

namespace App\Repositories;

use App\Models\Match;

class MatchRepository extends Repository
{
    /**
     * MatchRepository constructor.
     *
     * @param Match $match
     */
    public function __construct(Match $match)
    {
        parent::__construct($match);
    }

    /**
     * @param int $from
     * @param int $to
     *
     * @return mixed
     */
    public function getAllByStartTimestampDiapason($from, $to)
    {
        return $this->getModel()->where('start_time', '>=', $from)->where('start_time', '<=', $to)->get();
    }
}
