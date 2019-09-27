<?php

namespace App\Utils;

use App\Models\GroupGamePlayoff;
use App\Models\GroupGameRegular;
use App\Models\PersonalGamePlayoff;
use App\Models\PersonalGameRegular;
use App\Models\Team;
use DateTime;
use Exception;
use Image;

class ScoreImage
{
    const FILE_NAME = 'bar.jpg';
    const TEMPLATE = 'score_template.jpg';
    const FONT = 'hermesc_.ttf';
    const PADDING = 40;
    const CIRCLE_WIDTH = 140;
    //const CIRCLE_BACKGROUND = [255, 255, 255, 0.7];
    const CIRCLE_BACKGROUND = '#fff';
    const NO_STARS_OFFSET = 120;
    //const NO_STARS_OFFSET = 0;

    private $_img;
    /**
     * @var GroupGameRegular|GroupGamePlayoff|PersonalGameRegular|PersonalGamePlayoff
     */
    private $_game;
    private $_score = [];
    private $_team = [];
    private $_header = [];

    public function __construct($game)
    {
        $this->_game = $game;
        $this->_img = Image::make(storage_path() . '/' . self::TEMPLATE);
        $this->_score = [
            'font'      => storage_path() . '/' . self::FONT,
            'size'      => 100,
            'topOffset' => 5,
            'color'     => '#000',
        ];
        $this->_team = [
            'font'  => storage_path() . '/' . self::FONT,
            'size'  => 100,
            'color' => '#fff',
        ];
        $this->_header = [
            'font'  => storage_path() . '/' . self::FONT,
            'size'  => 30,
            'color' => '#000',
        ];
    }

    /**
     * @throws Exception
     */
    public function create()
    {
        $this->_makeTournamentTitle();
        $this->_makeHeader();
        $this->_makeTeamName($this->_game->homeTeam->team);
        $this->_makeScoreCircle($this->_game->home_score);
        $this->_makeColon();
        $this->_makeTeamName($this->_game->awayTeam->team, true);
        $this->_makeScoreCircle($this->_game->away_score, true);
        $this->_makeFooter();

        $this->_img->save(storage_path() . '/' . self::FILE_NAME);
    }

    private function _makeTournamentTitle()
    {
        $this->_img->text(
            $this->_game->tournament->title,
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

    /**
     * @throws Exception
     */
    private function _makeHeader()
    {
        $this->_img->line(
            0,
            60 + self::NO_STARS_OFFSET,
            $this->_img->width(),
            60 + self::NO_STARS_OFFSET,
            function ($draw) {
                $draw->color([255, 255, 255, 0.7]);
                $draw->width(100);
            }
        );
        $this->_img->text(
            'Тур ' . $this->_game->round,
            $this->_img->width() / 2,
            self::PADDING + self::NO_STARS_OFFSET,
            function ($font) {
                $font->file($this->_header['font']);
                $font->size($this->_header['size'] * 1.5);
                $font->color($this->_header['color']);
                $font->align('center');
                $font->valign('center');
            }
        );
        $this->_img->text(
            (new DateTime($this->_game->playedAt))->format('d.m.Y'),
            $this->_img->width() / 2,
            self::PADDING * 2.3 + self::NO_STARS_OFFSET,
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
     * @param Team $team
     * @param bool $isAway
     */
    private function _makeTeamName(Team $team, $isAway = false)
    {
        $spaces = substr_count($team->name, ' ');
        $fontSize = $this->_team['size'] / (!$spaces ? 1 : $spaces * 1.5);
        $x = $isAway
            ? $this->_img->width() / 2 + self::PADDING
            : $this->_img->width() / 2 - self::PADDING;
        $this->_img->text(
            str_replace(' ', "\r\n", $team->name),
            $x,
            $this->_img->height() / 2 - self::PADDING * ($spaces ? 6 : 4.8) + self::NO_STARS_OFFSET,
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
     * @param bool $isAway
     */
    private function _makeScoreCircle(int $score, $isAway = false)
    {
        $x = $isAway
            ? $this->_img->width() / 2 + self::CIRCLE_WIDTH / 2 + self::PADDING
            : $this->_img->width() / 2 - self::CIRCLE_WIDTH / 2 - self::PADDING;
        $this->_img->circle(
            self::CIRCLE_WIDTH,
            $x,
            $this->_img->height() / 2 - self::PADDING + self::NO_STARS_OFFSET,
            function ($draw) {
                $draw->background(self::CIRCLE_BACKGROUND);
            }
        );
        $this->_img->text(
            $score,
            $x,
            $this->_img->height() / 2 - self::PADDING + $this->_score['topOffset'] + self::NO_STARS_OFFSET,
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

    private function _makeColon()
    {
        $this->_img->text(
            ':',
            $this->_img->width() / 2,
            $this->_img->height() / 2 - self::PADDING + self::NO_STARS_OFFSET,
            function ($font) {
                $font->file($this->_score['font']);
                $font->size($this->_score['size']);
                $font->color(self::CIRCLE_BACKGROUND);
                $font->align('center');
                $font->valign('center');
            }
        );
    }

    private function _makeFooter()
    {
        $this->_img->text(
            'Хоккейная Киберспортивная Лига',
            $this->_img->width() / 2,
            $this->_img->height() / 2 + self::PADDING * 5 + self::NO_STARS_OFFSET,
            function ($font) {
                $font->file($this->_header['font']);
                $font->size($this->_header['size']);
                $font->color($this->_header['color']);
                $font->align('center');
                $font->valign('center');
            }
        );
    }
}
