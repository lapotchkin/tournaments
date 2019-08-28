<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupGameRegularPlayer
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
 * @property int|null              $isWin               Победа
 * @property int|null              $assists             Пасы
 * @property int                   $isGoalie            Вратарь
 * @property Carbon                $createdAt           Дата создания
 * @property Carbon|null           $deletedAt           Дата удаления
 * @property-read Player           $player
 * @property-read GroupGameRegular $regularGame
 * @property-read Team             $team
 * @property-read PlayerClass      $playerClass
 * @property-read PlayerPosition   $playerPosition
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupGameRegularPlayer newModelQuery()
 * @method static EloquentBuilder|GroupGameRegularPlayer newQuery()
 * @method static QueryBuilder|GroupGameRegularPlayer onlyTrashed()
 * @method static EloquentBuilder|GroupGameRegularPlayer query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupGameRegularPlayer whereAssists($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereBlocks($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereBreakeawaySaves($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereBreakeawayShots($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereClassId($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereCreatedAt($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereDeletedAt($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereFaceoffLose($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereFaceoffWin($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereGameId($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereGameWinningGoals($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereGiveaways($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereGoals($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereGoalsAgainst($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereHits($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereId($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereIsGoalie($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereIsWin($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePenaltyMinutes($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePenaltySaves($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePenaltyShots($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePlayerId($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePlusMinus($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePokechecks($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePositionId($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer wherePowerPlayGoals($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereRatingDefense($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereRatingOffense($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereRatingTeamplay($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereSaves($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereShorthandedGoals($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereShots($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereShotsOnGoal($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereTakeaways($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereTeamId($value)
 * @method static EloquentBuilder|GroupGameRegularPlayer whereTimeOnIceSeconds($value)
 * @method static QueryBuilder|GroupGameRegularPlayer withTrashed()
 * @method static QueryBuilder|GroupGameRegularPlayer withoutTrashed()
 * @mixin Eloquent
 */
class GroupGameRegularPlayer extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupGameRegular_player';

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
    ];

    /**
     * @return BelongsTo
     */
    public function regularGame()
    {
        return $this->belongsTo('App\Models\GroupGameRegular', 'game_id');
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
        return $this->belongsTo('App\Models\PlayerClass');
    }

    /**
     * @return BelongsTo
     */
    public function playerPosition()
    {
        return $this->belongsTo('App\Models\PlayerPosition', 'position_id');
    }
}
