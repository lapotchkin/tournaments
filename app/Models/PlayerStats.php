<?php


namespace App\Models;


use DB;
use stdClass;

class PlayerStats
{
    /**
     * @param int $playerId
     *
     * @return object
     */
    public static function readGroupPlayerStats(int $playerId)
    {
        $statsRegular = DB::select(self::getGroupPlayerStatsQuery('groupGameRegular_player'), [$playerId]);
        $statsPlayoff = DB::select(self::getGroupPlayerStatsQuery('groupGamePlayoff_player'), [$playerId]);

        $stats = self::combineResults($statsRegular, $statsPlayoff);

        $statsCount = count($stats->items);
        for ($i = 0; $i < $statsCount; $i += 1) {
            self::computeResulted($stats->items[$i]);
        }
        if (!is_null($stats->result)) {
            self::computeResulted($stats->result);
        }

        return $stats;
    }

    /**
     * @param int $playerId
     *
     * @return object
     */
    public static function readPersonalStats(int $playerId)
    {
        $statsRegular = DB::select(self::getPersonalStatsQuery('personalGameRegular'), [$playerId]);
        $statsPlayoff = DB::select(self::getPersonalStatsQuery('personalGamePlayoff'), [$playerId]);

        return self::combineResults($statsRegular, $statsPlayoff, false);
    }

    /**
     * @param int $playerId
     *
     * @return object
     */
    public static function readGroupGoalieStats(int $playerId)
    {
        $statsRegular = DB::select(self::getGroupGoalieStatsQuery('groupGameRegular'), [$playerId]);
        $statsPlayoff = DB::select(self::getGroupGoalieStatsQuery('groupGamePlayoff'), [$playerId]);

        return self::combineResults($statsRegular, $statsPlayoff);
    }

    /**
     * @param int $teamId
     *
     * @return object
     */
    public static function readTeamPlayersStats(int $teamId)
    {
        $statsRegular = DB::select(self::getTeamPlayersStatsQuery('groupGameRegular_player'), [$teamId]);
        $statsPlayoff = DB::select(self::getTeamPlayersStatsQuery('groupGamePlayoff_player'), [$teamId]);

        $stats = self::combineResults($statsRegular, $statsPlayoff);

        $statsCount = count($stats->items);
        for ($i = 0; $i < $statsCount; $i += 1) {
            self::computeResulted($stats->items[$i]);
        }
        if (!is_null($stats->result)) {
            self::computeResulted($stats->result);
        }

        return $stats;
    }

    /**
     * @param int $teamId
     *
     * @return object
     */
    public static function readTeamGoaliesStats(int $teamId)
    {
        $statsRegular = DB::select(self::getTeamGoaliesStatsQuery('groupGameRegular'), [$teamId]);
        $statsPlayoff = DB::select(self::getTeamGoaliesStatsQuery('groupGamePlayoff'), [$teamId]);

        return self::combineResults($statsRegular, $statsPlayoff);
    }

    /**
     * @param stdClass $statsRegular
     * @param stdClass $statsPlayoff
     *
     * @return stdClass
     */
    public static function setStats(stdClass $statsRegular, stdClass $statsPlayoff = null)
    {
        $stats = $statsRegular;
        if (!is_null($statsPlayoff)) {
            foreach ($statsPlayoff as $key => $stat) {
                if (in_array($key, ['id', 'name', 'tag', 'title'])) {
                    continue;
                }
                $stats->{$key} += $stat;
            }
        }

        return $stats;
    }

    /**
     * @param $stats
     */
    protected static function computeResulted($stats)
    {
        $stats->goals_per_game = round($stats->goals / $stats->games, 2);
        $stats->shots_per_game = $stats->shots_games
            ? round($stats->shots / $stats->shots_games, 2)
            : 0;
        $stats->assists_per_game = round($stats->assists / $stats->games, 2);
        $stats->blocks_per_game = $stats->blocks_games
            ? round($stats->blocks / $stats->blocks_games, 2)
            : 0;
        $stats->takeaways_per_game = $stats->takeaways_games
            ? round($stats->takeaways / $stats->takeaways_games, 2)
            : 0;
        $stats->interceptions_per_game = $stats->interceptions_games
            ? round($stats->interceptions / $stats->interceptions_games, 2)
            : 0;
        $stats->giveaways_per_game = $stats->giveaways_games
            ? round($stats->giveaways / $stats->giveaways_games, 2)
            : 0;
        $stats->hits_per_game = $stats->hits_games
            ? round($stats->hits / $stats->hits_games, 2)
            : 0;
        $stats->penalty_minutes_per_game = $stats->penalty_minutes_games
            ? round($stats->penalty_minutes / $stats->penalty_minutes_games, 2)
            : 0;
        $stats->rating_offense = $stats->rating_offense_games
            ? (int)round($stats->rating_offense / $stats->rating_offense_games)
            : 0;
        $stats->rating_teamplay = $stats->rating_teamplay_games
            ? (int)round($stats->rating_teamplay / $stats->rating_teamplay_games)
            : 0;
        $stats->rating_defense = $stats->rating_defense_games
            ? (int)round($stats->rating_defense / $stats->rating_defense_games)
            : 0;
        $stats->faceoff_win_percent = $stats->faceoff_win + $stats->faceoff_lose
            ? (int)round($stats->faceoff_win / ($stats->faceoff_win + $stats->faceoff_lose) * 100, 0)
            : 0;
        $stats->faceoff_lose_percent = 100 - $stats->faceoff_win_percent;
        $stats->pass_percent = $stats->pass_attempts
            ? (int)round($stats->passes / $stats->pass_attempts * 100, 0)
            : 0;

//        unset($stats->shots_games);
//        unset($stats->blocks_games);
//        unset($stats->takeaways_games);
//        unset($stats->giveaways_games);
//        unset($stats->hits_games);
//        unset($stats->penalty_minutes_games);
//        unset($stats->rating_offense_games);
//        unset($stats->rating_teamplay_games);
//        unset($stats->rating_defense_games);
    }

    /**
     * @param stdClass $a
     * @param stdClass $b
     *
     * @return int
     */
    protected static function sortTeamStats(stdClass $a, stdClass $b)
    {
        if ($a->games == $b->games) {
            return 0;
        }
        return ($a->games < $b->games) ? 1 : -1;
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected static function getGroupPlayerStatsQuery(string $table)
    {
        return "
            select t.id,
                   t.name,
                   count(1) games,
                   sum(gGRp.goals) goals,
                   sum(gGRp.assists) assists,
                   sum(gGRp.goals) + sum(gGRp.assists) points,
                   sum(gGRp.power_play_goals) power_play_goals,
                   sum(gGRp.shorthanded_goals) shorthanded_goals,
                   sum(gGRp.game_winning_goals) game_winning_goals,
                   sum(gGRp.plus_minus) plus_minus,
                   sum(gGRp.shots) shots,
                   sum(if(gGRp.shots is not null, 1, 0)) shots_games,
                   sum(gGRp.blocks) blocks,
                   sum(if(gGRp.blocks is not null, 1, 0)) blocks_games,
                   sum(gGRp.takeaways) takeaways,
                   sum(if(gGRp.takeaways is not null, 1, 0)) takeaways_games,
                   sum(gGRp.giveaways) giveaways,
                   sum(gGRp.interceptions) interceptions,
                   sum(if(gGRp.createdAt > '2021-01-09 00:00:00', 1, 0)) interceptions_games,
                   sum(if(gGRp.giveaways is not null, 1, 0)) giveaways_games,
                   sum(gGRp.hits) hits,
                   sum(if(gGRp.hits is not null, 1, 0)) hits_games,
                   sum(gGRp.penalty_minutes) penalty_minutes,
                   sum(if(gGRp.penalty_minutes is not null, 1, 0)) penalty_minutes_games,
                   sum(gGRp.rating_offense) rating_offense,
                   sum(if(gGRp.rating_offense is not null, 1, 0)) rating_offense_games,
                   sum(gGRp.rating_teamplay) rating_teamplay,
                   sum(if(gGRp.rating_teamplay is not null, 1, 0)) rating_teamplay_games,
                   sum(gGRp.rating_defense) rating_defense,
                   sum(if(gGRp.rating_defense is not null, 1, 0)) rating_defense_games,
                   sum(if(gGRp.star = 1, 1, 0)) first_star,
                   sum(if(gGRp.star = 2, 1, 0)) second_star,
                   sum(if(gGRp.star = 3, 1, 0)) third_star,
                   sum(gGRp.faceoff_win) faceoff_win,
                   sum(gGRp.faceoff_lose) faceoff_lose,
                   sum(gGRp.passes) passes,
                   sum(gGRp.pass_attempts) pass_attempts,
                   sum(if(gGRp.createdAt > '2021-01-09 00:00:00' is not null, 1, 0)) passes_games
            from team t
                     inner join {$table} gGRp on gGRp.team_id = t.id
            where gGRp.player_id = ?
              and gGRp.isGoalie <> 1
              and t.deletedAt is null
            group by t.id, t.name
            order by games desc
        ";
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected static function getPersonalStatsQuery(string $table)
    {
        $join = "inner join {$table} pGR on (p.id = pGR.away_player_id or p.id = pGR.home_player_id)
                        and pGR.tournament_id = pTp.tournament_id";
        if ($table === 'personalGamePlayoff') {
            $join = "
                inner join personalTournamentPlayoff pTP on pTP.tournament_id = pTp.tournament_id and pTP.deletedAt is null
                inner join {$table} pGR on (p.id = pGR.away_player_id or p.id = pGR.home_player_id)
                        and pGR.playoff_pair_id = pTP.id
            ";
        }


        return "
            select pTp.tournament_id id,
                   pT.title name,
                   count(1) games,
                   sum(if(pGR.home_player_id = p.id and pGR.home_score > pGR.away_score, 1, 0))
                       + sum(if(pGR.away_player_id = p.id and pGR.away_score > pGR.home_score, 1, 0)) wins,
                   sum(if(pGR.home_player_id = p.id and pGR.home_score < pGR.away_score, 1, 0))
                       + sum(if(pGR.away_player_id = p.id and pGR.away_score < pGR.home_score, 1, 0)) lose,
                   sum(if(pGR.home_player_id = p.id, pGR.home_score, pGR.away_score)) goals_for,
                   sum(if(pGR.home_player_id = p.id, pGR.away_score, pGR.home_score)) goals_against
            from player p
                     inner join personalTournament_player pTp on p.id = pTp.player_id and pTp.deletedAt is null
                     inner join personalTournament pT on pTp.tournament_id = pT.id and pT.deletedAt is null
                     {$join}
            where p.id = ?
                  and pGR.away_score is not null
                  and pGR.home_score is not null
                  and pGR.deletedAt is null
            group by pT.title, pTp.tournament_id, p.name, pT.createdAt
            order by pT.createdAt;
        ";
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected static function getGroupGoalieStatsQuery(string $table)
    {
        return "
            select t.id,
                   t.name,
                   count(1) games,
                   sum(if(gGR.home_team_id = t.id and gGR.home_score > gGR.away_score, 1, 0))
                       + sum(if(gGR.away_team_id = t.id and gGR.away_score > gGR.home_score, 1, 0)) wins,
                   sum(if(gGR.home_team_id = t.id and gGR.home_score < gGR.away_score, 1, 0))
                       + sum(if(gGR.away_team_id = t.id and gGR.away_score < gGR.home_score, 1, 0)) lose,
                   sum(if(gGR.home_team_id = t.id, gGR.away_score, gGR.home_score)) goals_against,
                   sum(if(gGR.home_team_id = t.id, gGR.away_shot, gGR.home_shot)) shot_against,
                   sum(if(gGR.home_team_id = t.id and gGR.away_score = 0, 1, 0))
                       + sum(if(gGR.away_team_id = t.id and gGR.home_score = 0, 1, 0)) shotouts
            from team t
                     inner join {$table}_player gGRp on gGRp.team_id = t.id
                     inner join {$table} gGR on gGRp.game_id = gGR.id and gGR.deletedAt is null
            where gGRp.player_id = ?
              and t.deletedAt is null
              and gGRp.isGoalie = 1
            group by t.id, t.name;
        ";
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected static function getTeamGoaliesStatsQuery(string $table)
    {
        return "
            select p.id,
                   p.tag,
                   p.name,
                   count(1) games,
                   sum(if(gGR.home_team_id = t.id and gGR.home_score > gGR.away_score, 1, 0))
                       + sum(if(gGR.away_team_id = t.id and gGR.away_score > gGR.home_score, 1, 0)) wins,
                   sum(if(gGR.home_team_id = t.id and gGR.home_score < gGR.away_score, 1, 0))
                       + sum(if(gGR.away_team_id = t.id and gGR.away_score < gGR.home_score, 1, 0)) lose,
                   sum(if(gGR.home_team_id = t.id, gGR.away_score, gGR.home_score)) goals_against,
                   sum(if(gGR.home_team_id = t.id, gGR.away_shot, gGR.home_shot)) shot_against,
                   sum(if(gGR.home_team_id = t.id and gGR.away_score = 0, 1, 0))
                       + sum(if(gGR.away_team_id = t.id and gGR.home_score = 0, 1, 0)) shotouts
            from team t
                     inner join {$table}_player gGRp on gGRp.team_id = t.id
                     inner join {$table} gGR on gGRp.game_id = gGR.id and gGR.deletedAt is null
                     inner join player p on gGRp.player_id = p.id and p.deletedAt is null
            where t.id = ?
              and gGRp.isGoalie = 1
              and t.deletedAt is null
            group by p.id, p.tag, p.name;
        ";
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected static function getTeamPlayersStatsQuery(string $table)
    : string
    {
        return "
            select p.id,
                   p.tag,
                   p.name,
                   count(1) games,
                   sum(gGRp.goals) goals,
                   sum(gGRp.assists) assists,
                   sum(gGRp.goals) + sum(gGRp.assists) points,
                   sum(gGRp.power_play_goals) power_play_goals,
                   sum(gGRp.shorthanded_goals) shorthanded_goals,
                   sum(gGRp.game_winning_goals) game_winning_goals,
                   sum(gGRp.plus_minus) plus_minus,
                   sum(gGRp.shots) shots,
                   sum(if(gGRp.shots is not null, 1, 0)) shots_games,
                   sum(gGRp.blocks) blocks,
                   sum(if(gGRp.blocks is not null, 1, 0)) blocks_games,
                   sum(gGRp.takeaways) takeaways,
                   sum(if(gGRp.takeaways is not null, 1, 0)) takeaways_games,
                   sum(gGRp.giveaways) giveaways,
                   sum(if(gGRp.giveaways is not null, 1, 0)) giveaways_games,
                   sum(gGRp.interceptions) interceptions,
                   sum(if(gGRp.createdAt > '2021-01-09 00:00:00', 1, 0)) interceptions_games,
                   sum(gGRp.hits) hits,
                   sum(if(gGRp.hits is not null, 1, 0)) hits_games,
                   sum(gGRp.penalty_minutes) penalty_minutes,
                   sum(if(gGRp.penalty_minutes is not null, 1, 0)) penalty_minutes_games,
                   sum(gGRp.rating_offense) rating_offense,
                   sum(if(gGRp.rating_offense is not null, 1, 0)) rating_offense_games,
                   sum(gGRp.rating_teamplay) rating_teamplay,
                   sum(if(gGRp.rating_teamplay is not null, 1, 0)) rating_teamplay_games,
                   sum(gGRp.rating_defense) rating_defense,
                   sum(if(gGRp.rating_defense is not null, 1, 0)) rating_defense_games,
                   sum(if(gGRp.star = 1, 1, 0)) first_star,
                   sum(if(gGRp.star = 2, 1, 0)) second_star,
                   sum(if(gGRp.star = 3, 1, 0)) third_star,
                   sum(gGRp.faceoff_win) faceoff_win,
                   sum(gGRp.faceoff_lose) faceoff_lose,
                   sum(gGRp.passes) passes,
                   sum(gGRp.pass_attempts) pass_attempts,
                   sum(if(gGRp.createdAt > '2021-01-09 00:00:00' is not null, 1, 0)) passes_games
            from team t
                     inner join {$table} gGRp on gGRp.team_id = t.id
            inner join player p on gGRp.player_id = p.id and  p.deletedAt is null
            where t.id = ?
              and gGRp.isGoalie <> 1
            group by p.id, p.tag, p.name
            order by games desc
        ";
    }

    /**
     * @param array $statsRegular
     * @param array $statsPlayoff
     * @param bool  $sort
     *
     * @return object
     */
    protected static function combineResults(array $statsRegular, array $statsPlayoff, bool $sort = true)
    {
        $stats = [];
        $resultStats = null;
        $statsRegularCount = count($statsRegular);
        for ($i = 0; $i < $statsRegularCount; $i += 1) {
            $stats[$statsRegular[$i]->id] = self::setStats($statsRegular[$i]);

            if ($i === 0) {
                $resultStats = clone $statsRegular[$i];
                $resultStats->id = null;
                $resultStats->name = 'Всего';
            } else {
                $resultStats = self::setStats($resultStats, $statsRegular[$i]);
            }
        }
        $statsPlayoffCount = count($statsPlayoff);
        for ($i = 0; $i < $statsPlayoffCount; $i += 1) {
            if ($i === 0 && is_null($resultStats)) {
                $resultStats = clone $statsPlayoff[$i];
            } else {
                $resultStats = self::setStats($resultStats, $statsPlayoff[$i]);
            }


            if (!isset($stats[$statsPlayoff[$i]->id])) {
                $stats[$statsPlayoff[$i]->id] = self::setStats($statsPlayoff[$i]);
                continue;
            }
            $stats[$statsPlayoff[$i]->id] = self::setStats($stats[$statsPlayoff[$i]->id], $statsPlayoff[$i]);
        }

        if ($sort) {
            usort($stats, "self::sortTeamStats");
        }

        return (object)[
            'items'  => $stats,
            'result' => $resultStats,
        ];
    }
}