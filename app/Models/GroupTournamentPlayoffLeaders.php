<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * Class GroupTournamentPlayoffLeaders
 * @package App\Models
 */
class GroupTournamentPlayoffLeaders
{
    /**
     * @param int         $tournamentId
     * @param string|null $date
     * @return array
     */
    public static function readLeaders(int $tournamentId, string $date = null)
    {
        $dateString = is_null($date) ? '' : "and gGPp.createdAt < '{$date}'";
        $leaders = DB::select("
            select leaders.*,
               (
                   select concat('<a href=\"/team/', t.id, '\">', t.name, '</a> <span class=\"badge badge-success\">',
                                 t.short_name, '</span>') name
                   from
                       groupGamePlayoff_player gGPp2
                           inner join groupGamePlayoff g on gGPp2.game_id = g.id
                           inner join groupTournamentPlayoff gTP2 on g.playoff_pair_id = gTP2.id and gTP2.tournament_id = ?
                           inner join team t on gGPp2.team_id = t.id
                   where gGPp2.player_id = leaders.id
                   order by gGPp2.id desc
                   limit 0,1
               ) team
            from
                (
                    select p.id,
                           concat('<a href=\"/player/', p.id, '\">', p.name, '</a> <small>', p.tag, '</small>') player,
                           count(gGPp.id) games,
                           sum(gGPp.goals) goals,
                           sum(gGPp.assists) assists,
                           sum(gGPp.assists + gGPp.goals) points
                    from
                        groupTournament gT
                            inner join groupTournamentPlayoff gTP on gT.id = gTP.tournament_id
                            inner join groupGamePlayoff gGP on gTP.id = gGP.playoff_pair_id and gGP.deletedAt is null
                            inner join groupGamePlayoff_player gGPp on gGP.id = gGPp.game_id and gGPp.isGoalie = 0
                            inner join player p on gGPp.player_id = p.id and p.deletedAt is null
                    where gT.id = ?
                        {$dateString}
                        and gT.deletedAt is null
                    group by gGPp.player_id
                ) leaders
            group by leaders.id, leaders.player
        ", [$tournamentId, $tournamentId]);

        return $leaders;
    }
}
