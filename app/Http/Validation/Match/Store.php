<?php

namespace App\Http\Validation\Match;

class Store extends Base
{
    /**
     * @return array
     */
    public function getAllowedFields()
    {
        return [
            'winner_id',
            'start_time',
            'end_time',
            'log',
            'players'
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'winner_id'  => 'required|integer',
            'start_time' => 'required|integer',
            'end_time'   => 'required|integer',
            'log'        => 'required',
            'players'    => 'required|array'
        ];
    }
}
