<?php

namespace App\Models;

use DB;

/**
 * Class GroupTournamentPlayoffPosition
 * @package App\Models
 */
class GroupTournamentPlayoffPosition
{
    /**
     * @param int $tournamentId
     * @return string|null
     */
    public static function readLastUpdateDate(int $tournamentId)
    {
        $result =  DB::table('groupGamePlayoff')
            ->select([
                DB::raw("DATE_FORMAT(updatedAt, '%Y-%m-%d 00:00:00') as date"),
            ])
            ->join('groupTournamentPlayoff', 'groupGamePlayoff.playoff_pair_id', '=', 'groupTournamentPlayoff.id')
            ->where('tournament_id', '=', $tournamentId)
            ->whereNull('groupGamePlayoff.deletedAt')
            ->whereNull('groupTournamentPlayoff.deletedAt')
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
                   t.id as id,
                   (if(w.wins > 0, w.wins, 0) + if(l.lose > 0, l.lose, 0)) games
            from team t
                 left join (
                    select
                        count(1) wins,
                        if(home_score > away_score, home_team_id, away_team_id) winner_team_id
                    from groupGamePlayoff gGP
                             inner join groupTournamentPlayoff gTP on gGP.playoff_pair_id = gTP.id and gTP.deletedAt is null
                    where tournament_id = ?
                         {$dateString}
                        and (home_score is not null and away_score is not null)
                        and gGP.deletedAt is null
                    group by winner_team_id
                ) w on t.id = w.winner_team_id
            
                 left join (
                    select
                        count(1) lose,
                        if(home_score < away_score, home_team_id, away_team_id) loser_team_id
                    from groupGamePlayoff gGP
                             inner join groupTournamentPlayoff gTP on gGP.playoff_pair_id = gTP.id and gTP.deletedAt is null
                    where tournament_id = ?
                         {$dateString}
                        and (home_score is not null and away_score is not null)
                        and gGP.deletedAt is null
                    group by loser_team_id
                ) l on t.id = l.loser_team_id
                 inner join groupTournament_team gTt on t.id = gTt.team_id and gTt.tournament_id = ? and gTt.deletedAt is null
            where t.deletedAt is null
            group by t.id
        ", [
            $tournamentId,
            $tournamentId,
            $tournamentId,
        ]);

        return $position;
    }
}
