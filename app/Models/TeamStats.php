<?php


namespace App\Models;


use DB;

class TeamStats
{
    /**
     * @param int $teamId
     *
     * @return array
     */
    public static function readStats(int $teamId)
    {
        $stats = DB::select("
            select t.id team_id,
                   t.name team_name,
                   (
                           if(w.wins > 0, w.wins, 0) +
                           if(wot.wins > 0, wot.wins, 0) +
                           if(lot.lose > 0, lot.lose, 0) +
                           if(l.lose > 0, l.lose, 0)
                       ) games,
                   if(w.wins > 0, w.wins, 0) wins,
                   if(wot.wins > 0, wot.wins, 0) wins_ot,
                   if(lot.lose > 0, lot.lose, 0) lose_ot,
                   if(l.lose > 0, l.lose, 0) lose,
                   (ifnull(home_stats.penalty_for, 0) + ifnull(away_stats.penalty_for, 0)) penalty_for,
                   (ifnull(home_stats.penalty_for_success, 0) + ifnull(away_stats.penalty_for_success, 0)) penalty_for_success,
                   (ifnull(home_stats.penalty_against, 0) + ifnull(away_stats.penalty_against, 0)) penalty_against,
                   (ifnull(home_stats.penalty_against_success, 0) +
                    ifnull(away_stats.penalty_against_success, 0)) penalty_against_success,
                   (ifnull(home_stats.faceoff_for, 0) + ifnull(away_stats.faceoff_for, 0)) /
                   (ifnull(home_stats.faceoff_for, 0) + ifnull(away_stats.faceoff_for, 0) + ifnull(home_stats.faceoff_against, 0) +
                    ifnull(away_stats.faceoff_against, 0)) * 100 faceoff,
                   (ifnull(home_stats.hit_for, 0) + ifnull(away_stats.hit_for, 0)) hit_for,
                   (ifnull(home_stats.shorthanded_goal, 0) + ifnull(away_stats.shorthanded_goal, 0)) shorthanded_goal
            from team t
                     left join (select count(1) wins, if(home_score > away_score, home_team_id, away_team_id) winner_team_id
                                from groupGameRegular
                                where (home_score is not null and away_score is not null)
                                  and (isOvertime = 0 and isShootout = 0)
                                  and deletedAt is null
                                group by winner_team_id) w on t.id = w.winner_team_id
                     left join (select count(1) wins, if(home_score > away_score, home_team_id, away_team_id) winner_team_id
                                from groupGameRegular
                                where (home_score is not null and away_score is not null)
                                  and (isOvertime = 1 and isShootout = 0)
                                  and deletedAt is null
                                group by winner_team_id) wot on t.id = wot.winner_team_id
                     left join (select count(1) lose, if(home_score < away_score, home_team_id, away_team_id) loser_team_id
                                from groupGameRegular
                                where (home_score is not null and away_score is not null)
                                  and (isOvertime = 1 and isShootout = 0)
                                  and deletedAt is null
                                group by loser_team_id) lot on t.id = lot.loser_team_id
                     left join (select count(1) lose, if(home_score < away_score, home_team_id, away_team_id) loser_team_id
                                from groupGameRegular
                                where (home_score is not null and away_score is not null)
                                  and (isOvertime = 0 and isShootout = 0)
                                  and deletedAt is null
                                group by loser_team_id) l on t.id = l.loser_team_id
                     left join (select home_team_id team_id,
                                       sum(home_penalty_total) penalty_for,
                                       sum(home_penalty_success) penalty_for_success,
                                       sum(away_penalty_total) penalty_against,
                                       sum(away_penalty_success) penalty_against_success,
                                       sum(home_faceoff) faceoff_for,
                                       sum(away_faceoff) faceoff_against,
                                       sum(home_hit) hit_for,
                                       sum(home_shorthanded_goal) shorthanded_goal
                                from groupGameRegular
                                where (home_score is not null and away_score is not null)
                                  and deletedAt is null
                                group by home_team_id) home_stats on home_stats.team_id = t.id
                     left join (select away_team_id team_id,
                                       sum(away_penalty_total) penalty_for,
                                       sum(away_penalty_success) penalty_for_success,
                                       sum(home_penalty_total) penalty_against,
                                       sum(home_penalty_success) penalty_against_success,
                                       sum(away_faceoff) faceoff_for,
                                       sum(home_faceoff) faceoff_against,
                                       sum(away_hit) hit_for,
                                       sum(away_shorthanded_goal) shorthanded_goal
                                from groupGameRegular
                                where (home_score is not null and away_score is not null)
                                  and deletedAt is null
                                group by away_team_id) away_stats on away_stats.team_id = t.id
            where t.id = ?
              and t.deletedAt is null
        ", [$teamId]);

        return $stats;
    }

    public static function readScoreDynamics(int $teamId)
    {
        $stats = DB::select("
            select gGR.id game_id,
                   if(gGR.home_team_id = t.id, gGR.home_score, gGR.away_score) goals_for,
                   if(gGR.home_team_id = t.id, gGR.away_score, gGR.home_score) goals_against,
                   if(
                       gGR.home_team_id = t.id, 
                       if (gGR.home_shot is null, gGR.home_score, gGR.home_shot),
                       if (gGR.away_shot is null, gGR.away_score, gGR.away_shot)
                    ) shots
            from team t
                     inner join groupGameRegular gGR
                                on (t.id = gGR.away_team_id or t.id = gGR.home_team_id)
                                       and gGR.away_score is not null
                                       and gGR.home_score is not null
                                       and gGR.deletedAt is null
            where t.id = ?
              and t.deletedAt is null;
        ", [$teamId]);

        return $stats;
    }

}