<?php

namespace App\Http\Validation\Match;

class Update extends Base
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
            'winner_id'  => 'sometimes|integer',
            'start_time' => 'sometimes|integer',
            'end_time'   => 'sometimes|integer',
            'players'    => 'sometimes|array'
        ];
    }
}
