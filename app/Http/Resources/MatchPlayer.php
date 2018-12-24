<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MatchPlayer extends Resource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            $this->player_id
        ];
    }
}
