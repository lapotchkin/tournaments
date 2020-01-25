<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupGamePlayoff
 *
 * @property int                                      $id                    ID
 * @property int                                      $playoff_pair_id       Пара в плейоф
 * @property int                                      $home_team_id          ID команды хозяеа
 * @property int                                      $away_team_id          ID команды гостей
 * @property int|null                                 $home_score            Забили гости
 * @property int|null                                 $away_score            Забили хозяева
 * @property int|null                                 $home_shot             Хозяева броски
 * @property int|null                                 $away_shot             Гости броски
 * @property int|null                                 $home_hit              Хозяева силовые
 * @property int|null                                 $away_hit              Гости силовые
 * @property string|null                              $home_attack_time      Хозяева время в атаке
 * @property string|null                              $away_attack_time      Гости время в атаке
 * @property float|null                               $home_pass_percent     Хозяева процент пасов
 * @property float|null                               $away_pass_percent     Гости процент пасов
 * @property int|null                                 $home_faceoff          Хозяева вбрасывания
 * @property int|null                                 $away_faceoff          Гости вбрасывания
 * @property string|null                              $home_penalty_time     Хозяева штрафные минуты
 * @property string|null                              $away_penalty_time     Гости штрафные минуты
 * @property int|null                                 $home_penalty_total    Хозяева всего большинство
 * @property int|null                                 $away_penalty_total    Гости всего большинство
 * @property int|null                                 $home_penalty_success  Хозяева реализовано большинство
 * @property int|null                                 $away_penalty_success  Гости реализовано большинство
 * @property string|null                              $home_powerplay_time   Хозяева время в большинстве
 * @property string|null                              $away_powerplay_time   Гости время в большинстве
 * @property int|null                                 $home_shorthanded_goal Хозяева голы в меньшинстве
 * @property int|null                                 $away_shorthanded_goal Гости голы в меньшинстве
 * @property int                                      $isTechnicalDefeat     Техническое поражение
 * @property string|null                              $playedAt              Дата игры
 * @property Carbon                                   $createdAt             Дата создания
 * @property Carbon|null                              $deletedAt             Дата удаления
 * @property Carbon|null                              $updatedAt             Дата редактирования
 * @property string|null                              $match_id              ID матча в EASHL
 * @property int                                      $isOvertime            Игра завершилась в овертайме
 * @property string|null                              $sharedAt
 * @property int                                      $isConfirmed           Результат подтверждён
 * @property int|null                                 $added_by          ID подтвердившей команды
 * @property-read GroupTournamentTeam                 $awayTeam
 * @property-read GroupTournamentTeam                 $homeTeam
 * @property-read GroupTournamentPlayoff              $playoffPair
 * @property-read Collection|GroupGamePlayoffPlayer[] $protocols
 * @property-read int|null                            $protocols_count
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupGamePlayoff newModelQuery()
 * @method static EloquentBuilder|GroupGamePlayoff newQuery()
 * @method static QueryBuilder|GroupGamePlayoff onlyTrashed()
 * @method static EloquentBuilder|GroupGamePlayoff query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayAttackTime($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayFaceoff($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayHit($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayPassPercent($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayPenaltySuccess($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayPenaltyTime($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayPenaltyTotal($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayPowerplayTime($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayScore($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayShorthandedGoal($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayShot($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereAwayTeamId($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereConfirmedBy($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereCreatedAt($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereDeletedAt($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomeAttackTime($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomeFaceoff($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomeHit($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomePassPercent($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomePenaltySuccess($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomePenaltyTime($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomePenaltyTotal($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomePowerplayTime($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomeScore($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomeShorthandedGoal($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomeShot($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereHomeTeamId($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereId($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereIsConfirmed($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereIsOvertime($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereIsTechnicalDefeat($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereMatchId($value)
 * @method static EloquentBuilder|GroupGamePlayoff wherePlayedAt($value)
 * @method static EloquentBuilder|GroupGamePlayoff wherePlayoffPairId($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereSharedAt($value)
 * @method static EloquentBuilder|GroupGamePlayoff whereUpdatedAt($value)
 * @method static QueryBuilder|GroupGamePlayoff withTrashed()
 * @method static QueryBuilder|GroupGamePlayoff withoutTrashed()
 * @mixin Eloquent
 */
class GroupGamePlayoff extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupGamePlayoff';

    /**
     * @var GroupGamePlayoffPlayer[]
     */
    public $homeProtocols = [];
    /**
     * @var GroupGamePlayoffPlayer[]
     */
    public $awayProtocols = [];
    /**
     * @var null|GroupGamePlayoffPlayer
     */
    public $homeGoalie = null;
    /**
     * @var null|GroupGamePlayoffPlayer
     */
    public $awayGoalie = null;

    /**
     * @var array
     */
    protected $fillable = [
        'playoff_pair_id',
        'home_team_id',
        'away_team_id',
        'home_score',
        'away_score',
        'home_shot',
        'away_shot',
        'home_hit',
        'away_hit',
        'home_attack_time',
        'away_attack_time',
        'home_pass_percent',
        'away_pass_percent',
        'home_faceoff',
        'away_faceoff',
        'home_penalty_time',
        'away_penalty_time',
        'home_penalty_total',
        'away_penalty_total',
        'home_penalty_success',
        'away_penalty_success',
        'home_powerplay_time',
        'away_powerplay_time',
        'home_shorthanded_goal',
        'away_shorthanded_goal',
        'isOvertime',
        'isTechnicalDefeat',
        'playedAt',
        'match_id',
        'sharedAt',
        //'createdAt',
        //'deletedAt',
        'isConfirmed',
        'added_by',
    ];

    /**
     * @return BelongsTo
     */
    public function playoffPair()
    {
        return $this->belongsTo('App\Models\GroupTournamentPlayoff', 'playoff_pair_id');
    }

    /**
     * @return BelongsTo
     */
    public function homeTeam()
    {
        return $this->belongsTo('App\Models\GroupTournamentTeam', 'home_team_id', 'team_id');
    }

    /**
     * @return BelongsTo
     */
    public function awayTeam()
    {
        return $this->belongsTo('App\Models\GroupTournamentTeam', 'away_team_id', 'team_id');
    }

    /**
     * @return HasMany
     */
    public function protocols()
    {
        return $this->hasMany('App\Models\GroupGamePlayoffPlayer', 'game_id');
    }

    /**
     * @return HasOneThrough
     */
    public function tournament()
    {
        return $this->hasOneThrough(
            'App\Models\GroupTournament',
            'App\Models\GroupTournamentPlayoff',
            'id',
            'id',
            'playoff_pair_id',
            'tournament_id'
        );
    }

    /**
     * @return array
     */
    public function getSafeProtocols()
    {
        $protocols = [
            'home' => [],
            'away' => [],
        ];
        foreach ($this->protocols as $protocol) {
            if ($protocol->team_id === $this->home_team_id) {
                $protocols['home'][] = $protocol->getSafeProtocol();
            } else {
                $protocols['away'][] = $protocol->getSafeProtocol();
            }
        }
        return $protocols;
    }

    /**
     * @return array
     */
    public function getSafePlayersData()
    {
        $players = [
            'home' => [],
            'away' => [],
        ];
        foreach ($this->homeTeam->team->players as $player) {
            $players['home'][] = $player->getSafeData();
        }
        foreach ($this->awayTeam->team->players as $player) {
            $players['away'][] = $player->getSafeData();
        }
        return $players;
    }

    /**
     * @return Collection
     */
    public function getStars()
    {
        $stars = new Collection;
        foreach ($this->protocols as $protocol) {
            if ($protocol->star > 0) {
                $stars->push($protocol);
            }
        }
        $stars = $stars->sortBy('star');
        return $stars;
    }
}
