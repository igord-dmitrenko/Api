<?php

namespace App\Helpers\Rating;

class Elo
{
    const DEFAULT_VALUE      = 1200;
    const COEFFICIENT_JUNIOR = 40;
    const COEFFICIENT_MIDDLE = 20;
    const COEFFICIENT_SENIOR = 10;

    /**
     * @param array $players
     * @param       $winnerId
     *
     * @return array
     */
    public function calculateRating(array $players, $winnerId)
    {
        foreach ($players as $key => $player) {
            $coefficient = $this->getCoefficient($player['rating'], $player['matchesCount']);
            if ($player['id'] == $winnerId) {
                $players[$key]['rating'] = $player['rating'] + $coefficient;
            } else {
                $players[$key]['rating'] = $player['rating'] - $coefficient;
            }
        }

        return $players;
    }

    /**
     * @param $rating
     * @param $matchCount
     *
     * @return int
     */
    private function getCoefficient($rating, $matchCount)
    {
        if ($matchCount <= 30) {
            return self::COEFFICIENT_JUNIOR;
        }

        if ($rating < 2400) {
            return self::COEFFICIENT_MIDDLE;
        }

        return self::COEFFICIENT_SENIOR;
    }
}
