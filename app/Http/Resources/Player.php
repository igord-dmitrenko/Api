<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Player extends Resource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'rating' => $this->rating
        ];
    }
}
