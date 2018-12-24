<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * @var string
     */
    protected $table = 'match';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany('App\Models\MatchPlayer', 'match_id', 'id');
    }

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->players()->delete();

        return parent::delete();
    }
}
