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
        $dateString = is_null($date) ? '' : "and gGPp.createdAt <= '{$date}'";
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
                           p.tag,
                           p.name,
                           concat('<a href=\"/player/', p.id, '\">', p.tag, '</a> <small>', p.name, '</small>') player,
                           count(gGPp.id) games,
                           sum(if(gGPp.position_id = 1, 1, 0)) defender_count,
                           sum(if(gGPp.position_id = 3, 1, 0)) left_count,
                           sum(if(gGPp.position_id = 4, 1, 0)) center_count,
                           sum(if(gGPp.position_id = 5, 1, 0)) right_count,
                           sum(gGPp.goals) goals,
                           sum(gGPp.assists) assists,
                           sum(gGPp.assists + gGPp.goals) points,
                           sum(gGPp.power_play_goals) power_play_goals,
                           sum(gGPp.shorthanded_goals) shorthanded_goals,
                           sum(gGPp.game_winning_goals) game_winning_goals,
                           sum(gGPp.shots) shots,
                           round(sum(gGPp.goals) / sum(gGPp.shots) * 100) shots_percent,
                           sum(gGPp.plus_minus) plus_minus,
                           round(sum(gGPp.faceoff_win) / (sum(gGPp.faceoff_win) + sum(gGPp.faceoff_lose)) * 100) faceoff_win_percent,
                           count(if(gGPp.star = 1, 1, 0)) first_star,
                           sum(gGPp.blocks) blocks,
                           round(sum(gGPp.blocks) / count(gGPp.id), 2) blocks_per_game,
                           sum(gGPp.takeaways) takeaways,
                           round(sum(gGPp.takeaways) / count(gGPp.id), 2) takeaways_per_game,
                           sum(gGPp.giveaways) giveaways,
                           round(sum(gGPp.giveaways) / count(gGPp.id), 2) giveaways_per_game,
                           sum(gGPp.hits) hits,
                           round(sum(gGPp.hits) / count(gGPp.id), 2) hits_per_game,
                           sum(gGPp.penalty_minutes) penalty_minutes,
                           round(sum(gGPp.penalty_minutes) / count(gGPp.id), 2) penalty_minutes_per_game,
                           round(avg(gGPp.rating_offense)) rating_offense,
                           round(avg(gGPp.rating_defense)) rating_defense,
                           round(avg(gGPp.rating_teamplay)) rating_teamplay
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
