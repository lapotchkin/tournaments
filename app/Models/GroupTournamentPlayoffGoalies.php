<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * Class GroupTournamentPlayoffGoalies
 * @package App\Models
 */
class GroupTournamentPlayoffGoalies
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
                    select concat('<a href=\"/team/', t.id, '\">', t.name, '</a> <span class=\"badge badge-success\">', t.short_name, '</span>') name
                    from
                        groupGamePlayoff_player gGPp2
                            inner join groupGamePlayoff gGP2 on gGPp2.game_id = gGP2.id
                            inner join groupTournamentPlayoff gTP2 on gGP2.playoff_pair_id = gTP2.id and gTP2.tournament_id = ?
                            inner join team t on gGPp2.team_id = t.id
                    where gGPp2.player_id = p.id
                    order by gGPp2.id desc
                    limit 0,1
                ) team,
                (
                    select t.id
                    from
                        groupGamePlayoff_player gGPp2
                            inner join groupGamePlayoff gGP2 on gGPp2.game_id = gGP2.id
                            inner join groupTournamentPlayoff gTP2 on gGP2.playoff_pair_id = gTP2.id and gTP2.tournament_id = ?
                            inner join team t on gGPp2.team_id = t.id
                    where gGPp2.player_id = p.id
                    order by gGPp2.id desc
                    limit 0,1
                ) team_id,
                count(p.name) games,
                sum(if(gGPp.team_id = gGP.home_team_id and gGP.home_score > gGP.away_score, 1, 0))
                    + sum(if(gGPp.team_id = gGP.away_team_id and gGP.away_score > gGP.home_score, 1, 0)) wins,
                sum(if(gGPp.team_id = gGP.home_team_id, gGP.away_shot, gGP.home_shot)) shot_against,
                sum(if(gGPp.team_id = gGP.home_team_id, gGP.away_score, gGP.home_score)) goal_against,
                sum(if(gGPp.team_id = gGP.home_team_id and gGP.away_score = 0, 1, 0))
                    + sum(if(gGPp.team_id = gGP.away_team_id and gGP.home_score = 0, 1, 0)) shootouts
            from
                groupGamePlayoff_player gGPp
                    inner join groupGamePlayoff gGP on gGPp.game_id = gGP.id and gGP.deletedAt is null
                    inner join groupTournamentPlayoff gTP on gGP.playoff_pair_id = gTP.id and gTP.tournament_id = ?
                    inner join player p on gGPp.player_id = p.id and p.deletedAt is null
            where gGPp.isGoalie = 1
            #     {$dateString}
            
            group by p.id, p.name, p.tag
            
            order by team, goalie
        ", [$tournamentId, $tournamentId, $tournamentId]);

        return $position;
    }
}
