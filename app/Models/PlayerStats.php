<?php


namespace App\Models;


use DB;
use stdClass;

class PlayerStats
{
    /**
     * @param int $playerId
     *
     * @return stdClass
     */
    public static function readPlayerGroupStats(int $playerId)
    {
        $statsRegular = DB::select(self::getPlayerGroupStatsQuery('groupGameRegular_player'), [$playerId]);
        $statsPlayoff = DB::select(self::getPlayerGroupStatsQuery('groupGamePlayoff_player'), [$playerId]);

        $stats = $statsRegular[0];
        foreach ($statsPlayoff[0] as $key => $stat) {
            $stats->{$key} += $stat;
        }
        $stats->goals_per_game = round($stats->goals / $stats->games, 2);
        $stats->shots_per_game = round($stats->shots / $stats->shots_games, 2);
        $stats->assists_per_game = round($stats->assists / $stats->games, 2);
        $stats->blocks_per_game = round($stats->blocks / $stats->blocks_games, 2);
        $stats->takeaways_per_game = round($stats->takeaways / $stats->takeaways_games, 2);
        $stats->giveaways_per_game = round($stats->giveaways / $stats->giveaways_games, 2);
        $stats->hits_per_game = round($stats->hits / $stats->hits_games, 2);
        $stats->penalty_minutes_per_game = round($stats->penalty_minutes / $stats->penalty_minutes_games, 2);
        $stats->rating_offense = (int)round($stats->rating_offense / $stats->rating_offense_games);
        $stats->rating_teamplay = (int)round($stats->rating_teamplay / $stats->rating_teamplay_games);
        $stats->rating_defense = (int)round($stats->rating_defense / $stats->rating_defense_games);
        $stats->faceoff_win_percent = (int)round($stats->faceoff_win / ($stats->faceoff_win + $stats->faceoff_lose) * 100, 0);
        $stats->faceoff_lose_percent = 100 - $stats->faceoff_win_percent;

        unset($stats->shots_games);
        unset($stats->blocks_games);
        unset($stats->takeaways_games);
        unset($stats->giveaways_games);
        unset($stats->hits_games);
        unset($stats->penalty_minutes_games);
        unset($stats->rating_offense_games);
        unset($stats->rating_teamplay_games);
        unset($stats->rating_defense_games);

        return $stats;
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected static function getPlayerGroupStatsQuery(string $table)
    {
        return "
            select
                   count(1) games,
                   sum(gGRp.goals) goals,
                   sum(gGRp.power_play_goals) power_play_goals,
                   sum(gGRp.shorthanded_goals) shorthanded_goals,
                   sum(gGRp.game_winning_goals) game_winning_goals,
                   sum(gGRp.assists) assists,
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
            from player p
                     inner join {$table} gGRp on p.id = gGRp.player_id
            where p.id = ?
        ";
    }
}