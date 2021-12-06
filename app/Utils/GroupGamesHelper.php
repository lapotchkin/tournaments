<?php

namespace App\Utils;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGamePlayoffPlayer;
use App\Models\GroupGameRegular;
use App\Models\GroupGameRegularPlayer;

class GroupGamesHelper
{
    /**
     * @param GroupGameRegular|GroupGamePlayoff $groupGame
     *
     * @return void
     */
    public static function setProtocols($groupGame)
    {
        foreach ($groupGame->protocols as $protocol) {
            if ($protocol->team_id === $groupGame->home_team_id) {
                $groupGame->homeProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $groupGame->homeGoalie = $protocol;
                }
            } else {
                $groupGame->awayProtocols[] = $protocol;
                if ($protocol->isGoalie) {
                    $groupGame->awayGoalie = $protocol;
                }
            }
        }

        usort($groupGame->homeProtocols, "self::sortProtocols");
        usort($groupGame->awayProtocols, "self::sortProtocols");
    }

    /**
     * @param GroupGameRegularPlayer|GroupGamePlayoffPlayer $a
     * @param GroupGameRegularPlayer|GroupGamePlayoffPlayer $b
     *
     * @return int
     */
    public static function sortProtocols($a, $b)
    : int
    {
        if ($a->position_id == $b->position_id) {
            return 0;
        }

        return ($a->position_id > $b->position_id) ? -1 : 1;
    }
}
