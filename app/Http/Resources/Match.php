<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Match extends Resource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'players'    => MatchPlayer::collection($this->players),
            'winner_id'  => $this->winner_id,
            'start_time' => $this->start_time,
            'end_time'   => $this->end_time,
            'log'        => $this->log
        ];
    }
}
