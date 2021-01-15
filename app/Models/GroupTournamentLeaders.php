<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * Class GroupTournamentLeaders
 *
 * @package App\Models
 */
class GroupTournamentLeaders
{
    /**
     * @param int         $tournamentId
     * @param string|null $date
     *
     * @return array
     */
    public static function readLeaders(int $tournamentId, string $date = null)
    {
        $dateString = is_null($date) ? '' : "and gGRp.createdAt <= '{$date}'";
        $leaders = DB::select("
            select leaders.*,
                   (
                       select concat('<a href=\"/team/', t.id, '\">', t.name, '</a> <span class=\"badge badge-success\">', t.short_name, '</span>') name
                       from groupGameRegular_player gGRp
                            inner join groupGameRegular gGR on gGRp.game_id = gGR.id and gGR.tournament_id = ?
                            inner join team t on gGRp.team_id = t.id
                       where gGRp.player_id = leaders.id
                       order by gGRp.id desc
                       limit 0,1
                   ) team
            from
                (
                    select p.id,
                           p.tag,
                           p.name,
                           concat('<a href=\"/player/', p.id, '\">', p.tag, '</a> <small>', p.name, '</small>') player,
                           count(gGRp.id) games,
                           sum(if(gGRp.position_id = 1, 1, 0)) defender_count,
                           sum(if(gGRp.position_id = 3, 1, 0)) left_count,
                           sum(if(gGRp.position_id = 4, 1, 0)) center_count,
                           sum(if(gGRp.position_id = 5, 1, 0)) right_count,
                           sum(gGRp.goals) goals,
                           sum(gGRp.assists) assists,
                           sum(gGRp.assists + gGRp.goals) points,
                           sum(gGRp.power_play_goals) power_play_goals,
                           sum(gGRp.shorthanded_goals) shorthanded_goals,
                           sum(gGRp.game_winning_goals) game_winning_goals,
                           sum(gGRp.shots) shots,
                           round(sum(gGRp.goals) / sum(gGRp.shots) * 100) shots_percent,
                           sum(gGRp.plus_minus) plus_minus,
                           round(sum(gGRp.faceoff_win) / (sum(gGRp.faceoff_win) + sum(gGRp.faceoff_lose)) * 100) faceoff_win_percent,
                           count(if(gGRp.star = 1, 1, 0)) first_star,
                           sum(gGRp.blocks) blocks,
                           round(sum(gGRp.blocks) / count(gGRp.id), 2) blocks_per_game,
                           sum(gGRp.takeaways) takeaways,
                           round(sum(gGRp.takeaways) / count(gGRp.id), 2) takeaways_per_game,
                           sum(gGRp.giveaways) giveaways,
                           round(sum(gGRp.giveaways) / count(gGRp.id), 2) giveaways_per_game,
                           sum(gGRp.hits) hits,
                           sum(gGRp.interceptions) interceptions,
                           round(sum(gGRp.interceptions) / count(gGRp.id), 2) interceptions_per_game,
                           round(sum(gGRp.hits) / count(gGRp.id), 2) hits_per_game,
                           sum(gGRp.penalty_minutes) penalty_minutes,
                           round(sum(gGRp.penalty_minutes) / count(gGRp.id), 2) penalty_minutes_per_game,
                           round(avg(gGRp.rating_offense)) rating_offense,
                           round(avg(gGRp.rating_defense)) rating_defense,
                           round(avg(gGRp.rating_teamplay)) rating_teamplay,
                           round(if(sum(gGRp.pass_attempts) > 0, sum(gGRp.passes) / sum(gGRp.pass_attempts) * 100, 0)) pass_percent
                    from
                        groupTournament gT
                            inner join groupGameRegular gGR on gT.id = gGR.tournament_id and gGR.deletedAt is null
                            inner join groupGameRegular_player gGRp
                                on gGR.id = gGRp.game_id
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
