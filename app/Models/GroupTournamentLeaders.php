<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * Class GroupTournamentLeaders
 * @package app\models
 */
class GroupTournamentLeaders
{
    /**
     * @param int         $tournamentId
     * @param string|null $date
     * @return array
     */
    public static function readLeaders(int $tournamentId, string $date = null)
    {
        $dateString = is_null($date) ? '' : "and gGRp.createdAt < '{$date}'";
        $leaders = DB::select("
            select leaders.*,
                   (
                       select t.name
                       from groupGameRegular_player gGRp
                            inner join groupGameRegular gGR on gGRp.game_id = gGR.id and gGR.tournament_id = ?
                            inner join team t on gGRp.team_id = t.id
                       where gGRp.player_id = leaders.id and gGRp.deletedAt is null
                       order by gGRp.id desc
                       limit 0,1
                   ) team
            from
                (
                    select p.id,
                           concat(p.name, ' (', p.tag, ')') player,
                           count(gGRp.id) games,
                           sum(gGRp.goals) goals,
                           sum(gGRp.assists) assists,
                           sum(gGRp.assists + gGRp.goals) points
                    from
                        groupTournament gT
                            inner join groupGameRegular gGR on gT.id = gGR.tournament_id and gGR.deletedAt is null
                            inner join groupGameRegular_player gGRp
                                on gGR.id = gGRp.game_id
                                       and gGRp.deletedAt is null
                                       and gGRp.isGoalie = 0
                            inner join player p on gGRp.player_id = p.id and p.deletedAt is null
                    where gT.id = ?
                        {$dateString}
                        and gT.deletedAt is null
                    group by gGRp.player_id
                ) leaders
            group by leaders.id, leaders.player
        ", [$tournamentId, $tournamentId]);

        return $leaders;
    }
}
