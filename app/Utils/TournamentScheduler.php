<?php

namespace App\Utils;

class TournamentScheduler
{
    /**
     * @param array $teams
     *
     * @return array
     * @author    D.D.M. van Zelst
     * @copyright 2012
     */
    public static function generate(array $teams)
    : array
    {
        if (count($teams) % 2 != 0) {
            array_push($teams, null);
        }
        $away = array_splice($teams, (count($teams) / 2));
        $home = $teams;
        $round = [];
        for ($i = 0; $i < count($home) + count($away) - 1; $i++) {
            for ($j = 0; $j < count($home); $j++) {
                if (is_null($home[$j]) || is_null($away[$j])) {
                    continue;
                }

                $round[$i][$j][] = $home[$j];
                $round[$i][$j][] = $away[$j];
            }
            if (count($home) + count($away) - 1 > 2) {
                $splicedArray = array_splice($home, 1, 1);
                $shiftedArray = array_shift($splicedArray);
                array_unshift($away, $shiftedArray);
                array_push($home, array_pop($away));
            }
        }

        return $round;
    }
}