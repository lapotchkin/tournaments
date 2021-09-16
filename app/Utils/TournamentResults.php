<?php

namespace App\Utils;

use DateInterval;
use DateTime;
use Exception;
use stdClass;

class TournamentResults
{
    /**
     * @param array      $currentPosition
     * @param array|null $previousPosition
     *
     * @return array
     * @throws Exception
     */
    public static function getPosition(array $currentPosition, array $previousPosition = null)
    : array
    {
        $currentPlaces = self::_getParticipantsPlaces($currentPosition);
        $previousPlaces = self::_getParticipantsPlaces($previousPosition);
        $position = [];
        foreach ($currentPosition as $participant) {
            $position[] = $participant->isPlayer
                ? self::_getPlayerData($participant, $currentPlaces, $previousPlaces)
                : self::_getTeamData($participant, $currentPlaces, $previousPlaces);
        }

        return $position;
    }

    /**
     * @param array|null $participants
     *
     * @return array
     */
    private static function _getParticipantsPlaces(?array $participants)
    : array
    {
        if (is_null($participants)) {
            return [];
        }

        $places = [];
        foreach ($participants as $participant) {
            if (!isset($places[$participant->division])) {
                $places[$participant->division] = [];
            }
            if (in_array($participant->id, $places[$participant->division])) {
                continue;
            }
            $places[$participant->division][] = $participant->id;
        }

        return $places;
    }

    /**
     * @param stdClass $participant
     * @param array    $currentPlaces
     * @param array    $previousPlaces
     *
     * @return string
     */
    private static function _getPrevPlace(stdClass $participant, array $currentPlaces, array $previousPlaces)
    : string
    {
        $prevPlace = '—';
        if (
            isset($previousPlaces[$participant->division])
            && in_array($participant->id, $previousPlaces[$participant->division])
        ) {
            $prevPlace = (array_search($participant->id, $previousPlaces[$participant->division]) + 1)
                - (array_search($participant->id, $currentPlaces[$participant->division]) + 1);
        }

        return self::_getPrevPlaceHtml($prevPlace);
    }

    /**
     * @param $prevPlace
     *
     * @return string
     */
    private static function _getPrevPlaceHtml($prevPlace)
    : string
    {
        if ($prevPlace !== '—' && $prevPlace > 0) {
            return "<span class='text-success text-nowrap'>$prevPlace<i class='fas fa-long-arrow-alt-up'></i></span>";
        } elseif ($prevPlace === 0) {
            return '<i class="fas fa-arrows-alt-h"></i>';
        } elseif ($prevPlace < 0) {
            $prevPlace = str_replace('-', '', $prevPlace);
            return "<span class='text-danger text-nowrap'>$prevPlace<i class='fas fa-long-arrow-alt-down'></i></span>";
        }

        return '<span class="text-primary"><i class="fas fa-arrow-right"></i></span>';
    }

    /**
     * @param stdClass $player
     * @param array    $currentPlaces
     * @param array    $previousPlaces
     *
     * @return object
     */
    private static function _getPlayerData(stdClass $player, array $currentPlaces, array $previousPlaces)
    : object
    {
        $goalsDif = $player->goals - $player->goals_against;

        return (object)[
            'place'                  => array_search($player->id, $currentPlaces[$player->division]) + 1,
            'prevPlace'              => self::_getPrevPlace($player, $currentPlaces, $previousPlaces),
            'id'                     => $player->id,
            'player'                 => self::_getPlayerLink($player),
            'division'               => $player->division,
            'games'                  => $player->games,
            'points'                 => $player->points,
            'wins'                   => $player->wins,
            'wins_ot'                => $player->wins_ot,
            'wins_so'                => $player->wins_so,
            'lose_ot'                => $player->lose_ot,
            'lose_so'                => $player->lose_so,
            'lose'                   => $player->lose,
            'goals_diff'             => $goalsDif > 0 ? '+' . $goalsDif : $goalsDif,
            'goals'                  => $player->goals,
            'goals_per_game'         => $player->games > 0
                ? round($player->goals / $player->games, 2)
                : 0.00,
            'goals_against_per_game' => $player->games > 0
                ? round($player->goals_against / $player->games, 2)
                : 0.00,
        ];
    }

    /**
     * @param stdClass $player
     *
     * @return string
     */
    private static function _getPlayerLink(stdClass $player)
    : string
    {
        $link = route('player', ['player' => $player->id]);
        $playerName = "<a href=\"$link\">$player->tag</a> <small>$player->name</small>";

        return is_null($player->club_id)
            ? $playerName
            : "$playerName <span class=\"badge rounded-pill bg-success text-uppercase\">$player->club_id</span>";
    }

    /**
     * @param stdClass $team
     * @param array    $currentPlaces
     * @param array    $previousPlaces
     *
     * @return object
     * @throws Exception
     */
    private static function _getTeamData(stdClass $team, array $currentPlaces, array $previousPlaces)
    : object
    {
        $goalsDif = $team->goals - $team->goals_against;
        $attackSec = $team->games > 0 ? round($team->attack_time / $team->games) : 0;
        $attackTime = new DateTime();
        $attackTime->setTime(0, 0);
        $attackTime->add(new DateInterval('PT' . $attackSec . 'S'));

        return (object)[
            'place'                  => array_search($team->id, $currentPlaces[$team->division]) + 1,
            'prevPlace'              => self::_getPrevPlace($team, $currentPlaces, $previousPlaces),
            'id'                     => $team->id,
            'team'                   => self::_getTeamLink($team),
            'division'               => $team->division,
            'games'                  => $team->games,
            'points'                 => $team->points,
            'wins'                   => $team->wins,
            'wins_ot'                => $team->wins_ot,
            'wins_so'                => $team->wins_so,
            'lose_ot'                => $team->lose_ot,
            'lose_so'                => $team->lose_so,
            'lose'                   => $team->lose,
            'goals_diff'             => $goalsDif > 0 ? '+' . $goalsDif : $goalsDif,
            'goals'                  => $team->goals,
            'goals_per_game'         => $team->games > 0
                ? round($team->goals / $team->games, 2)
                : 0.00,
            'goals_against_per_game' => $team->games > 0
                ? round($team->goals_against / $team->games, 2)
                : 0.00,
            'powerplay'              => $team->penalty_for > 0
                ? round($team->penalty_for_success / $team->penalty_for * 100, 1) . '%'
                : '0.0%',
            'penalty_kill'           => $team->penalty_against > 0 ?
                100 - round($team->penalty_against_success / $team->penalty_against * 100, 1) . '%'
                : '0.0%',
            'shots_for'              => $team->shots_for,
            'shots_against'          => $team->shots_against,
            'shots_for_per_game'     => $team->games > 0
                ? round($team->shots_for / $team->games, 1)
                : 0.0,
            'shots_against_per_game' => $team->games > 0
                ? round($team->shots_against / $team->games, 1)
                : 0.0,
            'faceoff'                => round($team->faceoff, 1) . '%',
            'hit_for_per_game'       => $team->games > 0
                ? round($team->hit_for / $team->games, 1)
                : 0.0,
            'hit_against_per_game'   => $team->games > 0
                ? round($team->hit_against / $team->games, 1)
                : 0.0,
            'shorthanded_goal'       => $team->shorthanded_goal,
            'attack_time'            => $attackTime->format('i:s'),
            'pass_percent'           => round($team->pass_percent, 1) . '%',
        ];
    }

    /**
     * @param stdClass $team
     *
     * @return string
     */
    private static function _getTeamLink(stdClass $team)
    : string
    {
        $link = route('team', ['team' => $team->id]);

        return "<a href=\"$link\">$team->name</a> <span class=\"badge rounded-pill bg-success text-uppercase\">$team->short_name</span>";
    }

    /**
     * @param array      $currentLeaders
     * @param array|null $previousLeaders
     *
     * @return object
     */
    public static function getLeaders(array $currentLeaders, array $previousLeaders = null)
    : object
    {
        $order = [
            'points'  => ['points', 'goals', 'games'],
            'goals'   => ['goals', 'points', 'games'],
            'assists' => ['assists', 'points', 'games'],
        ];

        foreach ($currentLeaders as &$player) {
            $player->position = '';

            if ($player->center_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 4, 'short_title' => 'ЦЕН: ' . $player->center_count]);
            }
            if ($player->left_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 3, 'short_title' => 'ЛЕВ: ' . $player->left_count]);
            }
            if ($player->right_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 5, 'short_title' => 'ПРАВ: ' . $player->right_count]);
            }
            if ($player->defender_count > 0) {
                $player->position .= ' '
                    . TextUtils::positionBadge((object)['id' => 1, 'short_title' => 'ЗАЩ: ' . $player->defender_count]);
            }
            $player->position = trim($player->position);
        }
        unset($player);

        $leaders = (object)[];
        foreach ($order as $key => $sort) {
            //так сделано, потому что иначе позиция ставится по последней сорнтировке
            $leaders->{$key} = json_decode(json_encode($currentLeaders));
            array_multisort(
                array_column($leaders->{$key}, $sort[0]),
                SORT_DESC,
                array_column($leaders->{$key}, $sort[1]),
                SORT_DESC,
                array_column($leaders->{$key}, $sort[2]),
                SORT_ASC,
                $leaders->{$key}
            );

            $previousPlaces = [];
            if (!is_null($previousLeaders)) {
                array_multisort(
                    array_column($previousLeaders, $sort[0]),
                    SORT_DESC,
                    array_column($previousLeaders, $sort[1]),
                    SORT_DESC,
                    array_column($previousLeaders, $sort[2]),
                    SORT_ASC,
                    $previousLeaders
                );

                $ppc = count($previousLeaders);
                for ($i = 0; $i < $ppc; $i += 1) {
                    if (!isset($previousPlaces[$previousLeaders[$i]->id])) {
                        $previousPlaces[$previousLeaders[$i]->id] = $i;
                    }
                }
            }

            $pc = count($leaders->{$key});
            for ($i = 0; $i < $pc; $i += 1) {
                $prevPlace = isset($previousPlaces[$leaders->{$key}[$i]->id])
                    ? ($previousPlaces[$leaders->{$key}[$i]->id] + 1) - ($i + 1)
                    : '—';
                $leaders->{$key}[$i]->place = $i + 1;
                $leaders->{$key}[$i]->prevPlace = self::_getPrevPlaceHtml($prevPlace);
            }
        }

        return $leaders;
    }

    /**
     * @param array      $currentGoalies
     * @param array      $currentStats
     * @param array|null $previousGoalies
     * @param array|null $previousStats
     *
     * @return array
     */
    public static function getGoalies(
        array $currentGoalies,
        array $currentStats,
        array $previousGoalies = null,
        array $previousStats = null
    )
    : array
    {
        $currentGames = [];
        foreach ($currentStats as $stat) {
            $currentGames[$stat->id] = $stat->games;
        }

        $previousPlaces = [];
        if (!is_null($previousGoalies) && !is_null($previousStats)) {
            $previousGames = [];
            foreach ($previousStats as $stat) {
                $previousGames[$stat->id] = $stat->games;
            }
            $prev = [];
            foreach ($previousGoalies as $goalie) {
                if (!$previousGames[$goalie->team_id] || $goalie->games / $previousGames[$goalie->team_id] <= 0.25) {
                    continue;
                }
                $prev[] = $goalie;
                $goalie->saves_percent = round(
                    ($goalie->shot_against - $goalie->goal_against) / $goalie->shot_against,
                    3
                );
            }
            array_multisort(
                array_column($prev, 'saves_percent'),
                SORT_DESC,
                array_column($prev, 'games'),
                SORT_ASC,
                array_column($prev, 'shootouts'),
                SORT_DESC,
                $prev
            );
            $pc = count($prev);
            for ($i = 0; $i < $pc; $i += 1) {
                if (!isset($previousPlaces[$prev[$i]->id])) {
                    $previousPlaces[$prev[$i]->id] = $i;
                }
            }
        }

        $goalies = [];
        $goaliesAll = [];
        foreach ($currentGoalies as $goalie) {
            $goalie->loses = $goalie->games - $goalie->wins;
            $goalie->saves = $goalie->shot_against - $goalie->goal_against;
            $goalie->saves_percent = round(
                ($goalie->shot_against - $goalie->goal_against) / $goalie->shot_against,
                3
            );
            $goalie->goal_against_per_game = round($goalie->goal_against / $goalie->games, 2);

            if ($goalie->games / $currentGames[$goalie->team_id] >= 0.25) {
                $goalies[] = $goalie;
            }
            $goaliesAll[] = clone $goalie;
        }

        array_multisort(
            array_column($goalies, 'saves_percent'),
            SORT_DESC,
            array_column($goalies, 'games'),
            SORT_ASC,
            array_column($goalies, 'shootouts'),
            SORT_DESC,
            $goalies
        );
        array_multisort(
            array_column($goaliesAll, 'tag'),
            SORT_ASC,
            $goaliesAll
        );
        $place = 1;
        foreach ($goalies as $goalie) {
            $prevPlace = isset($previousPlaces[$goalie->id])
                ? ($previousPlaces[$goalie->id] + 1) - ($place)
                : '—';
            $goalie->place = $place;
            $goalie->prevPlace = self::_getPrevPlaceHtml($prevPlace);
            $place += 1;
        }

        $placeAll = 1;
        foreach ($goaliesAll as $goalieAll) {
            $goalieAll->place = $placeAll;
            $goalieAll->prevPlace = '';
            $placeAll += 1;
        }

        return [
            'top' => $goalies,
            'all' => $goaliesAll,
        ];
    }
}
