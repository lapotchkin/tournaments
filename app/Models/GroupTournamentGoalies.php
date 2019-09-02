<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * Class GroupTournamentGoalies
 * @package App\Models
 */
class GroupTournamentGoalies
{
    /**
     * @param int         $tournamentId
     * @param string|null $date
     * @return array
     */
    public static function readGoalies(int $tournamentId, string $date = null)
    {
        $dateString = is_null($date) ? '' : "and gGRp.createdAt < '{$date}'";
        $position = DB::select("
            select
                p.id,
                concat('<a href=\"/player/', p.id, '\">', p.name, '</a> <small>', p.tag, '</small>') goalie,
                (
                    select concat('<a href=\"/ team /', t.id, '\">', t.name, '</a> <span class=\"badge badge-success\">', t.short_name, '</span>') name
                    from
                        groupGameRegular_player gGRp2
                            inner join groupGameRegular gGP2 on gGRp2.game_id = gGP2.id and gGP2.tournament_id = ?
                            inner join team t on gGRp2.team_id = t.id
                    where gGRp2.player_id = p.id
                    order by gGRp2.id desc
                    limit 0,1
                ) team,
                (
                    select t.id
                    from
                        groupGameRegular_player gGRp2
                            inner join groupGameRegular gGP2 on gGRp2.game_id = gGP2.id and gGP2.tournament_id = ?
                            inner join team t on gGRp2.team_id = t.id
                    where gGRp2.player_id = p.id
                    order by gGRp2.id desc
                    limit 0,1
                ) team_id,
                count(p.name) games,
                sum(if(gGRp.team_id = gGR.home_team_id and gGR.home_score > gGR.away_score, 1, 0))
                    + sum(if(gGRp.team_id = gGR.away_team_id and gGR.away_score > gGR.home_score, 1, 0)) wins,
                sum(if(gGRp.team_id = gGR.home_team_id, gGR.away_shot, gGR.home_shot)) shot_against,
                sum(if(gGRp.team_id = gGR.home_team_id, gGR.away_score, gGR.home_score)) goal_against,
                sum(if(gGRp.team_id = gGR.home_team_id and gGR.away_score = 0, 1, 0))
                    + sum(if(gGRp.team_id = gGR.away_team_id and gGR.home_score = 0, 1, 0)) shootouts
            from
                groupGameRegular_player gGRp
                    inner join groupGameRegular gGR on gGRp.game_id = gGR.id and gGR.tournament_id = ? and gGR.deletedAt is null
                    inner join player p on gGRp.player_id = p.id and p.deletedAt is null
            where gGRp.isGoalie = 1
                 {$dateString}
            
            group by p.id, p.name, p.tag
            
            order by team, goalie
        ", [$tournamentId, $tournamentId, $tournamentId]);

        return $position;
    }
}
