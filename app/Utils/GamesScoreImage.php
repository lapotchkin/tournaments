<?php


namespace App\Utils;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\GroupTournament;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\PersonalTournament;
use App\Models\Player;
use App\Models\Team;
use DateTime;
use Illuminate\Support\Collection;
use Image;
use TextUtils;

/**
 * Class GamesScoreImage
 *
 * @package App\Utils
 */
class GamesScoreImage
{
    const FILE_NAME = 'bar.jpg';
    const TEMPLATE = 'score_template.jpg';
    const FONT = 'hermesc_.ttf';
    const PADDING = 40;
    const CIRCLE_WIDTH = 40;
    const BAR_HEIGHT = 50;
    //const CIRCLE_BACKGROUND = [255, 255, 255, 0.7];
    const CIRCLE_BACKGROUND = '#fff';
    const NO_STARS_OFFSET = 120;
    //const NO_STARS_OFFSET = 0;

    /**
     * @var Image
     */
    private $_img;
    /**
     * @var GroupGameRegular[]|GroupGamePlayoff[]|PersonalGameRegular[]|PersonalGamePlayoff[]|Collection
     */
    private $_games = [];
    /**
     * @var GroupTournament|PersonalTournament
     */
    private $_tournament;
    /**
     * @var bool
     */
    private $_isPlayoff;
    private $_header = [];

    public function __construct($games, $tournament, bool $isPlayoff)
    {
        $this->_games = $games;
        $this->_tournament = $tournament;
        $this->_isPlayoff = $isPlayoff;
        $this->_img = Image::make(storage_path() . '/' . self::TEMPLATE);
        $this->_header = [
            'font'  => storage_path() . '/' . self::FONT,
            'size'  => 30,
            'color' => '#fff',
        ];
        $this->_score = [
            'font'      => storage_path() . '/' . self::FONT,
            'size'      => 30,
            'topOffset' => 5,
            'color'     => '#000',
        ];
        $this->_team = [
            'font'  => storage_path() . '/' . self::FONT,
            'size'  => 25,
            'color' => '#000',
        ];
    }

    public function create()
    {
        $this->_makeTournamentTitle();
        $round = null;
        $offset = 0;
        foreach ($this->_games as $game) {
            if ($round !== $this->_getRound($game)) {
                $round = $this->_getRound($game);
                $this->_makeRoundTitle($round, $offset);
            }
            $this->_makeGame($game, $offset);
            $offset += self::BAR_HEIGHT + 10;
        }

        $path = storage_path() . '/' . self::FILE_NAME;
        $this->_img->save($path);
        return $path;
    }

    /**
     * @param GroupGameRegular|GroupGamePlayoff|PersonalGameRegular|PersonalGamePlayoff $game
     *
     * @return int|mixed|null
     */
    private function _getRound($game)
    {
        return $this->_isPlayoff ? $game->playoffPair->round : $game->round;
    }

    private function _makeTournamentTitle()
    {
        $this->_img->text(
            $this->_tournament->platform->name . ': ' . $this->_tournament->title,
            $this->_img->width() / 2,
            self::PADDING * 2,
            function ($font) {
                $font->file($this->_header['font']);
                $font->size($this->_header['size'] * 2);
                $font->color('#fff');
                $font->align('center');
                $font->valign('center');
            }
        );
    }

    private function _makeRoundTitle(int $round, int $offset)
    {
        $this->_makePolygon($this->_getPlatformColor(), $offset, true);
        $this->_img->text(
            $this->_isPlayoff
                ? TextUtils::playoffRound($this->_tournament, $round, true)
                : 'Тур ' . $round,
            120 + (250 - 120 - 60) / 2,
            self::PADDING / 1.6 + self::NO_STARS_OFFSET + $offset,
            function ($font) {
                $font->file($this->_header['font']);
                $font->size($this->_header['size']);
                $font->color($this->_header['color']);
                $font->align('center');
                $font->valign('center');
            }
        );
    }

    /**
     * @param GroupGameRegular|GroupGamePlayoff|PersonalGameRegular|PersonalGamePlayoff $game
     * @param int                                                                       $offset
     */
    private function _makeGame($game, int $offset)
    {
        $this->_makePolygon([255, 255, 255, 0.7], $offset);
        $this->_makeTeamName(isset($game->homeTeam) ? $game->homeTeam->team : $game->homePlayer, $offset);
        $this->_makeScore($game->home_score, $offset);
        $this->_makeScore($game->away_score, $offset, true);
        $this->_makeTeamName(isset($game->awayTeam) ? $game->awayTeam->team : $game->awayPlayer, $offset, true);
        $this->_makeWinStatus($game, $offset);
    }

    private function _makePolygon(array $color, int $offset, bool $isRound = false)
    {
        $delta = 250;
        $height = self::BAR_HEIGHT;
        $this->_img->polygon(
            [
                $isRound ? 120 : $delta + 10, self::NO_STARS_OFFSET + $offset,
                $isRound ? $delta : $this->_img->width() - 60, self::NO_STARS_OFFSET + $offset,
                $isRound ? $delta - 60 : $this->_img->width() - 120, self::NO_STARS_OFFSET + $offset + $height,
                $isRound ? 60 : $delta - 60 + 10, self::NO_STARS_OFFSET + $offset + $height,
            ],
            function ($draw) use ($color) {
                $draw->background($color);
            }
        );
    }

    private function _getScoreBarCenter()
    {
        return $this->_img->width() / 2 + 70;
    }

    /**
     * @param Team|Player $entity
     * @param int         $offset
     * @param bool        $isAway
     */
    private function _makeTeamName($entity, int $offset, $isAway = false)
    {
        $text = $entity->name;
        $fontSize = $this->_team['size'];
        $x = $isAway
            ? $this->_getScoreBarCenter() + self::PADDING + 30
            : $this->_getScoreBarCenter() - self::PADDING - 30;
        $this->_img->text(
            $text,
            $x,
            self::PADDING / 1.6 + self::NO_STARS_OFFSET + $offset,
            function ($font) use ($isAway, $fontSize) {
                $font->file($this->_team['font']);
                $font->size($fontSize);
                $font->color($this->_team['color']);
                $font->align($isAway ? 'left' : 'right');
                $font->valign('center');
            }
        );
    }

    /**
     * @param int  $score
     * @param int  $offset
     * @param bool $isAway
     */
    private function _makeScore(int $score, int $offset, $isAway = false)
    {
        $height = 50;
        $x = $isAway
            ? $this->_getScoreBarCenter() + self::CIRCLE_WIDTH / 2 + 7
            : $this->_getScoreBarCenter() - self::CIRCLE_WIDTH / 2 - 7;
        $this->_img->polygon(
            [
                $x - 25, self::NO_STARS_OFFSET + $offset,
                $x + 25, self::NO_STARS_OFFSET + $offset,
                $x + 25, self::NO_STARS_OFFSET + $offset + $height,
                $x - 25, self::NO_STARS_OFFSET + $offset + $height,
            ],
            function ($draw) {
                $draw->background('#fff');
            }
        );
        $this->_img->text(
            $score,
            $x,
            self::PADDING / 1.6 + self::NO_STARS_OFFSET + $offset,
            function ($font) {
                $font->file($this->_score['font']);
                $font->size($this->_score['size']);
                $font->color($this->_score['color']);
                $font->align('center');
                $font->valign('center');
                //$font->angle(45);
            }
        );
    }

    private function _getPlatformColor()
    {
        switch ($this->_tournament->platform_id) {
            case 'xboxone':
                return [56, 193, 114, 1];
            case 'playstation4':
                return [52, 144, 220, 1];
            default:
                return [255, 255, 255, 1];
        }
    }

    private function _makeWinStatus($game, int $offset)
    {
        $text = null;
        $background = null;
        $color = '#fff';
        if ($game->isOvertime) {
            $text = 'ОТ';
            $background = '#ff0';
            $color = '#000';
        } elseif ($game->isShootout) {
            $text = 'Б';
            $background = '#f00';
        } elseif ($game->isTechnicalDefeat) {
            $text = 'ТП';
            $background = '#000';
        }
        if (!$text) {
            return;
        }

        $height = 50;
        $this->_img->polygon(
            [
                $this->_img->width() - 120 - 40 + 20, self::NO_STARS_OFFSET + $offset,
                $this->_img->width() - 60, self::NO_STARS_OFFSET + $offset,
                $this->_img->width() - 120, self::NO_STARS_OFFSET + $offset + $height,
                $this->_img->width() - 180 - 40 + 20, self::NO_STARS_OFFSET + $offset + $height,
            ],
            function ($draw) use ($background) {
                $draw->background($background);
            }
        );
        $this->_img->text(
            $text,
            $this->_img->width() - 130,
            self::PADDING / 1.6 + self::NO_STARS_OFFSET + $offset,
            function ($font) use ($color) {
                $font->file($this->_header['font']);
                $font->size($this->_header['size']);
                $font->color($color);
                $font->align('center');
                $font->valign('center');
            }
        );
    }
}