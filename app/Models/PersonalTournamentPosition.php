<?php


namespace App\Models;

use DB;

/**
 * Class PersonalTournamentPosition
 * @package App\Models
 */
class PersonalTournamentPosition
{
    /**
     * @param int $tournamentId
     * @return string|null
     */
    public static function readLastUpdateDate(int $tournamentId)
    {
        $result = DB::table('personalGameRegular')
            ->select([
                DB::raw("DATE_FORMAT(updatedAt, '%Y-%m-%d 00:00:00') as date"),
            ])
            ->where('tournament_id', '=', $tournamentId)
            ->whereNull('deletedAt')
            ->orderByDesc('updatedAt')
            ->first();

        return is_null($result) ? null : $result->date;
    }

    /**
     * @param int         $tournamentId
     * @param string|null $date
     * @return mixed
     */
    public static function readPosition(int $tournamentId, string $date = null)
    {
        $dateString = is_null($date) ? '' : "and updatedAt < '{$date}'";
        $position = DB::select("
            select
                if (
                    pTp.club_id is null,
                    concat('<a href=\"/player/', p.id ,'\">', p.name, '</a> <small>', p.tag, '</small>'),
                    concat('<a href=\"/player/', p.id ,'\">', p.name, '</a> <small>', p.tag, '</small> <span class=\"badge badge-pill badge-success text-uppercase\">', pTp.club_id, '</span>')
                ) as player,
                p.id as id,
                pTp.division as division,
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
                (ifnull(home_stats.goals_for, 0) + ifnull(away_stats.goals_for, 0) - ifnull(home_stats.goals_against, 0) - ifnull(away_stats.goals_against, 0)) golas_diff,
                (ifnull(home_stats.goals_for, 0) + ifnull(away_stats.goals_for, 0)) goals,
                (ifnull(home_stats.goals_against, 0) + ifnull(away_stats.goals_against, 0)) goals_against
            from player p
                inner join personalTournament_player pTp on p.id = pTp.player_id
                    and pTp.tournament_id = ?
                     and pTp.deletedAt is null
                left join (
                    select count(1) wins,
                           if(home_score > away_score, home_player_id, away_player_id) winner_player_id
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null
                        and away_score is not null)
                        and (isOvertime = 0 and isShootout = 0)
                        and deletedAt is null
                    group by winner_player_id
                ) w on p.id = w.winner_player_id
            
                left join (
                    select count(1) wins,
                           if(home_score > away_score, home_player_id, away_player_id) winner_player_id
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 1 and isShootout = 0)
                        and deletedAt is null
                    group by winner_player_id
                ) wot on p.id = wot.winner_player_id
            
                left join (
                    select count(1) wins,
                           if(home_score > away_score, home_player_id, away_player_id) winner_player_id
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 0 and isShootout = 1)
                        and deletedAt is null
                    group by winner_player_id
                ) wso on p.id = wso.winner_player_id
            
                left join (
                    select count(1) lose,
                           if(home_score < away_score, home_player_id, away_player_id) loser_player_id
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 1 and isShootout = 0)
                        and deletedAt is null
                    group by loser_player_id
                ) lot on p.id = lot.loser_player_id
            
                left join (
                    select count(1) lose,
                           if(home_score < away_score, home_player_id, away_player_id) loser_player_id
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 0 and isShootout = 1)
                        and deletedAt is null
                    group by loser_player_id
                ) lso on p.id = lso.loser_player_id
            
                left join (
                    select count(1) lose,
                           if(home_score < away_score, home_player_id, away_player_id) loser_player_id
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null and away_score is not null)
                        and (isOvertime = 0 and isShootout = 0)
                        and deletedAt is null
                    group by loser_player_id
                ) l on p.id = l.loser_player_id
            
                left join (
                    select home_player_id player_id,
                           sum(home_score) goals_for,
                           sum(away_score) goals_against
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null and away_score is not null)
                        and deletedAt is null
                    group by home_player_id
                ) home_stats on home_stats.player_id = p.id
            
                left join (
                    select away_player_id player_id,
                           sum(away_score) goals_for,
                           sum(home_score) goals_against
                    from personalGameRegular
                    where tournament_id = ?
                        {$dateString}
                        and (home_score is not null and away_score is not null)
                        and deletedAt is null
                    group by away_player_id
                ) away_stats on away_stats.player_id = p.id
            where p.deletedAt is null
            group by p.id
            order by points desc,
                     (if(w.wins > 0, w.wins, 0) + if(wot.wins > 0, wot.wins, 0) + if(wso.wins > 0, wso.wins, 0)) desc,
                     w.wins desc,
                     (ifnull(home_stats.goals_for, 0) + ifnull(away_stats.goals_for, 0) - ifnull(home_stats.goals_against, 0) - ifnull(away_stats.goals_against,0)) desc,
                     (ifnull(home_stats.goals_for, 0) + ifnull(away_stats.goals_for, 0)) desc,
                     l.lose asc
        ", [
            $tournamentId,
            $tournamentId,
            $tournamentId,
            $tournamentId,
            $tournamentId,
            $tournamentId,
            $tournamentId,
            $tournamentId,
            $tournamentId,
            $tournamentId,
        ]);

        return $position;
    }
}
