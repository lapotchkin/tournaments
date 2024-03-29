<?php


namespace App\Utils;

use App\Http\Controllers\Ajax\TeamController;
use App\Models\GroupTournament;
use App\Models\PersonalTournament;

/**
 * Class TextUtils
 * @package App\Utils
 */
class TextUtils
{
    /**
     * @param int $division
     * @return mixed
     */
    public static function divisionLetter(int $division)
    {
        $letters = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
        ];

        return isset($letters[$division - 1]) ? $letters[$division - 1] : '—';
    }

    /**
     * @param string $time
     * @return string|string[]|null
     */
    public static function protocolTime(string $time = null)
    {
        if (is_null($time)) {
            return '';
        }
        return preg_replace('/^00:/', '', $time, 1);
    }

    /**
     * @param $position
     * @return string
     */
    public static function positionBadge($position)
    {
        if (is_null($position)) {
            return '';
        }
        $badgeClass = '';
        switch ($position->id) {
            case 0:
                $badgeClass = 'badge-goalie';
                break;
            case 1:
                $badgeClass = 'badge-defender';
                break;
            case 3:
                $badgeClass = 'badge-left_wing';
                break;
            case 4:
                $badgeClass = 'badge-center';
                break;
            case 5:
                $badgeClass = 'badge-right_wing';
                break;
        }
        return "<span class=\"badge {$badgeClass}\">{$position->short_title}</span>";
    }

    /**
     * @param GroupTournament|PersonalTournament $tournament
     * @param int                                $round
     * @param bool                               $isText
     * @return string
     */
    public static function playoffRound($tournament, int $round, bool $isText = false)
    {
        $maxCompetitors = pow(2, $tournament->playoff_rounds);
        switch ($maxCompetitors / pow(2, $round)) {
            case 8:
                return ($isText ? '1/8' : '⅛') . ' финала';
            case 4:
                return ($isText ? '1/4' : '¼') . ' финала';
            case 2:
                return ($isText ? '1/2' : '½') . ' финала';
            default:
                if ($tournament->thirdPlaceSeries && $tournament->playoff_rounds === $round) {
                    return '3-е место';
                }
                return 'Финал';
        }
    }

    /**
     * @param int $isConfirmed
     * @return string
     */
    public static function gameClass(int $isConfirmed = null)
    {
        switch ($isConfirmed) {
            case (1):
                return 'table-success';
            default:
                return 'table-danger';
        }
    }

    /**
     * @param int $place
     * @return string
     */
    public static function winnerClass(int $place)
    {
        switch ($place) {
            case (1):
                return 'warning';
            case (2):
                return 'secondary';
            default:
                return 'danger';
        }
    }

    /**
     * @param int $iteration
     * @param int $total
     * @return string
     */
    public static function playoffClass(int $iteration, int $total)
    {
        if ($iteration === $total) {
            return 'tournament-bracket__final';
        }
        if ($iteration === $total - 1) {
            return 'tournament-bracket__3rdplace';
        }

        return '';
    }

    /**
     * @param $action
     * @return string
     */
    public static function transferClass($action)
    {
        switch ($action) {
            case TeamController::ADD_TO_TEAM:
                return 'success';
            case TeamController::DELETE_FROM_TEAM:
                return 'danger';
            case TeamController::SET_AS_CAPTAIN:
                return 'info';
            case TeamController::SET_AS_ASSISTANT:
                return 'warning';
            case TeamController::SET_AS_PLAYER:
                return 'secondary';
        }

        return 'secondary';
    }
}
