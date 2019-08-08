<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

/**
 * Class GroupTournamentPosition
 * @package App\Models
 */
class GroupTournamentPosition
{
    /**
     * @param $id
     * @return mixed
     */
    public static function readPosition($id)
    {
        $position = DB::select('
            select t.name as team,
                t.id as id,
                (
                    if(w.wins > 0, w.wins, 0) +
                    if(wot.wins > 0, wot.wins, 0) +
                    if(wso.wins > 0, wso.wins, 0) +
                    if(lot.lose > 0, lot.lose, 0) +
                    if(lso.lose > 0, lso.lose, 0) +
                    if(l.lose > 0, l.lose, 0)
                ) games,
                (
                    if(w.wins > 0, w.wins, 0) * 2 +
                    if(wot.wins > 0, wot.wins, 0) * 2 +
                    if(wso.wins > 0, wso.wins, 0) * 2 +
                    if(lot.lose > 0, lot.lose, 0) +
                    if(lso.lose > 0, lso.lose, 0)
                ) points,
                if(w.wins > 0, w.wins, 0) wins,
                if(wot.wins > 0, wot.wins, 0) wins_ot,
                if(wso.wins > 0, wso.wins, 0) wins_so,
                if(lot.lose > 0, lot.lose, 0) lose_ot,
                if(lso.lose > 0, lso.lose, 0) lose_so,
                if(l.lose > 0, l.lose, 0) lose,
                (home_stats.goals_for + away_stats.goals_for) goals,
                (home_stats.goals_against + away_stats.goals_against) goals_against,
                (home_stats.shot_for + away_stats.shot_for) shots_for,
                (home_stats.shot_against + away_stats.shot_against) shots_against,
                (home_stats.penalty_for + away_stats.penalty_for) penalty_for,
                (home_stats.penalty_for_success + away_stats.penalty_for_success) penalty_for_success,
                (home_stats.penalty_against + away_stats.penalty_against) penalty_against,
                (home_stats.penalty_against_success + away_stats.penalty_against_success) penalty_against_success,
                (home_stats.faceoff_for + away_stats.faceoff_for) / (home_stats.faceoff_for + away_stats.faceoff_for + home_stats.faceoff_against + away_stats.faceoff_against) * 100 faceoff,
                (home_stats.hit_for + away_stats.hit_for) hit_for,
                (home_stats.hit_against + away_stats.hit_against) hit_against,
                (home_stats.shorthanded_goal + away_stats.shorthanded_goal) shorthanded_goal,
                (home_stats.attack_time + away_stats.attack_time) attack_time,
                avg((home_stats.pass_percent + away_stats.pass_percent) /2) pass_percent
            from team t
            
                left join (
                    select
                        count(1) wins,
                        if(home_score > away_score, home_team_id, away_team_id) winner_team_id
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 0 and isShootout = 0)
                        and deletedAt is null
                    group by winner_team_id
                ) w on t.id = w.winner_team_id
            
                left join (
                    select
                        count(1) wins,
                        if(home_score > away_score, home_team_id, away_team_id) winner_team_id
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 1 and isShootout = 0)
                        and deletedAt is null
                    group by winner_team_id
                ) wot on t.id = wot.winner_team_id
            
                left join (
                    select
                        count(1) wins,
                        if(home_score > away_score, home_team_id, away_team_id) winner_team_id
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 0 and isShootout = 1)
                        and deletedAt is null
                    group by winner_team_id
                ) wso on t.id = wso.winner_team_id
            
                left join (
                    select
                        count(1) lose,
                        if(home_score < away_score, home_team_id, away_team_id) loser_team_id
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 1 and isShootout = 0)
                        and deletedAt is null
                    group by loser_team_id
                ) lot on t.id = lot.loser_team_id
            
                left join (
                    select
                        count(1) lose,
                        if(home_score < away_score, home_team_id, away_team_id) loser_team_id
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 0 and isShootout = 1)
                        and deletedAt is null
                    group by loser_team_id
                ) lso on t.id = lso.loser_team_id
            
                left join (
                    select
                        count(1) lose,
                        if(home_score < away_score, home_team_id, away_team_id) loser_team_id
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 0 and isShootout = 0)
                        and deletedAt is null
                    group by loser_team_id
                ) l on t.id = l.loser_team_id
            
                left join (
                    select
                        home_team_id team_id,
                        sum(home_score) goals_for,
                        sum(away_score) goals_against,
                        sum(home_shot) shot_for,
                        sum(away_shot) shot_against,
                        sum(home_penalty_total) penalty_for,
                        sum(home_penalty_success) penalty_for_success,
                        sum(away_penalty_total) penalty_against,
                        sum(away_penalty_success) penalty_against_success,
                        sum(home_faceoff) faceoff_for,
                        sum(away_faceoff) faceoff_against,
                        sum(home_hit) hit_for,
                        sum(away_hit) hit_against,
                        sum(home_shorthanded_goal) shorthanded_goal,
                        sum(time_to_sec(home_attack_time)) attack_time,
                        avg(home_pass_percent) pass_percent
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and deletedAt is null
                    group by home_team_id
                ) home_stats on home_stats.team_id = t.id
            
                left join (
                    select
                        away_team_id team_id,
                        sum(away_score) goals_for,
                        sum(home_score) goals_against,
                        sum(away_shot) shot_for,
                        sum(home_shot) shot_against,
                        sum(away_penalty_total) penalty_for,
                        sum(away_penalty_success) penalty_for_success,
                        sum(home_penalty_total) penalty_against,
                        sum(home_penalty_success) penalty_against_success,
                        sum(away_faceoff) faceoff_for,
                        sum(home_faceoff) faceoff_against,
                        sum(away_hit) hit_for,
                        sum(home_hit) hit_against,
                        sum(away_shorthanded_goal) shorthanded_goal,
                        sum(time_to_sec(away_attack_time)) attack_time,
                        avg(away_pass_percent) pass_percent
                    from groupGameRegular
                    where tournament_id = ?
                        and (home_score is not null and away_score is not null)
                        and deletedAt is null
                    group by away_team_id
                ) away_stats on away_stats.team_id = t.id
            inner join groupTournament_team gTt on t.id = gTt.team_id and gTt.tournament_id = ? and gTt.deletedAt is null
            where t.deletedAt is null
            group by t.id
            order by
                points desc,
                (if(w.wins > 0, w.wins, 0) + if(wot.wins > 0, wot.wins, 0) + if(wso.wins > 0, wso.wins, 0)) desc,
                w.wins desc,
                (home_stats.goals_for + away_stats.goals_for - home_stats.goals_against - away_stats.goals_against) desc,
                (home_stats.goals_for + away_stats.goals_for) desc,
                l.lose
        ', [$id, $id, $id, $id, $id, $id, $id, $id, $id]);

        return $position;
    }
}
