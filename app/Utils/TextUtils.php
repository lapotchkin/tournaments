<?php


namespace App\Utils;

use App\Models\GroupTournament;

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
     * @param $positionId
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
     * @param GroupTournament $tournament
     * @param int             $round
     * @return string
     */
    public static function playoffRound(GroupTournament $tournament, int $round)
    {
        $maxTeams = pow(2, $tournament->playoff_rounds);
        switch ($maxTeams / pow(2, $round)) {
            case 8:
                return '⅛ финала';
            case 4:
                return '¼ финала';
            case 2:
                return '½ финала';
            default:
                if ($tournament->thirdPlaceSeries && $tournament->playoff_rounds === $round) {
                    return '3-е место';
                }
                return 'Финал';
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
}
