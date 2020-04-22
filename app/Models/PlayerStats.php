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
    public static function readPlayerTeamStats(int $playerId)
    {
        $statsRegular = DB::select(self::getPlayerTeamsStatsQuery('groupGameRegular_player'), [$playerId]);
        $statsPlayoff = DB::select(self::getPlayerTeamsStatsQuery('groupGamePlayoff_player'), [$playerId]);

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

        usort($stats, "self::sortPlayerTeamStats");

        $statsCount = count($stats);
        for ($i = 0; $i < $statsCount; $i += 1) {
            self::computeResulted($stats[$i]);
        }
        if (!is_null($resultStats)) {
            self::computeResulted($resultStats);
        }

        return (object)[
            'teams'  => $stats,
            'result' => $resultStats,
        ];
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
                if (in_array($key, ['id', 'name'])) {
                    continue;
                }
                $stats->{$key} += $stat;
            }
        }

        return $stats;
    }

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
    protected static function sortPlayerTeamStats(stdClass $a, stdClass $b)
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
    protected static function getPlayerTeamsStatsQuery(string $table)
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
                   sum(gGRp.faceoff_lose) faceoff_lose
            from team t
                     inner join {$table} gGRp on gGRp.team_id = t.id
            where gGRp.player_id = ?
              and t.deletedAt is null
            group by t.id, t.name
            order by games desc
        ";
    }
}