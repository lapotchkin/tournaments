<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use stdClass;

/**
 * App\Models\GroupGamePlayoffPlayer
 *
 * @property int                   $id                  ID
 * @property int                   $game_id             ID игры
 * @property int                   $team_id             ID команды
 * @property int                   $player_id           ID игрока
 * @property int|null              $class_id            ID класса игрока
 * @property int|null              $position_id         ID позиции игрока
 * @property int                   $star                Звезда матча
 * @property int|null              $time_on_ice_seconds Игровое время в секундах
 * @property int|null              $goals               Голы
 * @property int|null              $power_play_goals    Голы в большинстве
 * @property int|null              $shorthanded_goals   Голы в меньшиестве
 * @property int|null              $game_winning_goals  Победный гол
 * @property int|null              $shots               Броски в створ ворот
 * @property int|null              $plus_minus          +/-
 * @property int|null              $faceoff_win         Выиграно вбрасываний
 * @property int|null              $faceoff_lose        Проиграно вбрасываний
 * @property int|null              $blocks              Заблокировал бросков
 * @property int|null              $giveaways           Потери шайбы
 * @property int|null              $takeaways           Перехваты шайбы
 * @property int|null              $hits                Силовые приёмы
 * @property int|null              $penalty_minutes     Штрафные минуты
 * @property float|null            $rating_defense      Рейтинг защиты
 * @property float|null            $rating_offense      Рейтинг нападения
 * @property float|null            $rating_teamplay     Рейтинг командной игры
 * @property int|null              $shots_on_goal       Броски по воротам вратаря
 * @property int|null              $saves               Отбито бросков вратарём
 * @property int|null              $breakeaway_shots    Броски по воротам вратаря 1 на 1
 * @property int|null              $breakeaway_saves    Отбито бросков вратарём_1_на_1
 * @property int|null              $penalty_shots       Буллиты
 * @property int|null              $penalty_saves       Отбито буллитов
 * @property int|null              $goals_against       Пропущено голов
 * @property int|null              $pokechecks          Покчек (тычки клюшкой)
 * @property int|null              $shot_attempts       Попытки бросков
 * @property int|null              $deflections         Отклонения
 * @property int|null              $interceptions       Перехваты шайбы
 * @property int|null              $pass_attempts       Попытки паса
 * @property int|null              $passes              Удачные пасы
 * @property int|null              $saucer_passes       Пасы подкидкой
 * @property int|null              $clear_zone          Выбросы шайбы из зоны
 * @property int|null              $possession          Владение шайбой в секундах
 * @property int|null              $penalties_drawn     Количество штрафов
 * @property int|null              $isWin               Победа
 * @property int|null              $assists             Пасы
 * @property int                   $isGoalie            Вратарь
 * @property Carbon                $createdAt           Дата создания
 * @property Carbon|null           $deletedAt           Дата удаления
 * @property-read GroupGamePlayoff $playoffGame
 * @property-read Player           $player
 * @property-read Team             $team
 * @property-read PlayerClass      $playerClass
 * @property-read PlayerPosition   $playerPosition
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupGamePlayoffPlayer newModelQuery()
 * @method static EloquentBuilder|GroupGamePlayoffPlayer newQuery()
 * @method static QueryBuilder|GroupGamePlayoffPlayer onlyTrashed()
 * @method static EloquentBuilder|GroupGamePlayoffPlayer query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereAssists($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereBlocks($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereBreakeawaySaves($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereBreakeawayShots($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereClassId($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereCreatedAt($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereDeletedAt($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereFaceoffLose($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereFaceoffWin($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereGameId($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereGameWinningGoals($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereGiveaways($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereGoals($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereGoalsAgainst($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereHits($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereId($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereIsGoalie($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereIsWin($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePenaltyMinutes($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePenaltySaves($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePenaltyShots($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePlayerId($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePlusMinus($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePokechecks($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePositionId($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer wherePowerPlayGoals($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereRatingDefense($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereRatingOffense($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereRatingTeamplay($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereSaves($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereShorthandedGoals($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereShots($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereShotsOnGoal($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereTakeaways($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereTeamId($value)
 * @method static EloquentBuilder|GroupGamePlayoffPlayer whereTimeOnIceSeconds($value)
 * @method static QueryBuilder|GroupGamePlayoffPlayer withTrashed()
 * @method static QueryBuilder|GroupGamePlayoffPlayer withoutTrashed()
 * @mixin Eloquent
 */
class GroupGamePlayoffPlayer extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupGamePlayoff_player';

    /**
     * @var array
     */
    protected $fillable = [
        'game_id',
        'team_id',
        'player_id',
        'class_id',
        'position_id',
        'star',
        'time_on_ice_seconds',
        'goals',
        'power_play_goals',
        'shorthanded_goals',
        'game_winning_goals',
        'shots',
        'plus_minus',
        'faceoff_win',
        'faceoff_lose',
        'blocks',
        'giveaways',
        'takeaways',
        'hits',
        'penalty_minutes',
        'rating_defense',
        'rating_offense',
        'rating_teamplay',
        'shots_on_goal',
        'saves',
        'breakeaway_shots',
        'breakeaway_saves',
        'penalty_shots',
        'penalty_saves',
        'goals_against',
        'pokechecks',
        'isWin',
        'assists',
        'isGoalie',
        'createdAt',
        'shot_attempts',
        'deflections',
        'interceptions',
        'pass_attempts',
        'passes',
        'saucer_passes',
        'clear_zone',
        'possession',
        'penalties_drawn',
    ];

    /**
     * @return BelongsTo
     */
    public function playoffGame()
    {
        return $this->belongsTo('App\Models\GroupGamePlayoff', 'game_id');
    }

    /**
     * @return BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * @return BelongsTo
     */
    public function playerClass()
    {
        return $this->belongsTo('App\Models\PlayerClass', 'class_id');
    }

    /**
     * @return BelongsTo
     */
    public function playerPosition()
    {
        return $this->belongsTo('App\Models\PlayerPosition', 'position_id');
    }

    /**
     * @return stdClass
     */
    public function getSafeProtocol()
    {
        $protocol = new stdClass;
        $protocol->id = $this->id;
        foreach ($this->fillable as $field) {
            $protocol->{$field} = $this->{$field};
        }
        $protocol->name = $this->player->name;
        $protocol->player_tag = $this->player->tag;
        $protocol->position = $this->playerPosition ? (object)[
            'title'       => $this->playerPosition->title,
            'short_title' => $this->playerPosition->short_title,
        ] : null;

        return $protocol;
    }
}
