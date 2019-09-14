<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupGameRegular
 *
 * @property int                                      $id                    ID
 * @property int                                      $tournament_id         ID турнира
 * @property int|null                                 $round                 Круг
 * @property int                                      $home_team_id          ID команды хозяев
 * @property int                                      $away_team_id          ID команды гостей
 * @property int|null                                 $home_score            Забили хозяева
 * @property int|null                                 $away_score            Забили гости
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
 * @property int                                      $isOvertime            Овертайм
 * @property int                                      $isShootout            Буллиты
 * @property int                                      $isTechnicalDefeat     Техническое поражение
 * @property Carbon                                   $createdAt             Дата создания
 * @property string|null                              $playedAt              Дата игры
 * @property string|null                              $updatedAt             Дата изменения
 * @property Carbon|null                              $deletedAt             Дата удаления
 * @property string|null                              $match_id              ID матча в EASHL
 * @property-read GroupTournamentTeam                 $awayTeam
 * @property-read GroupTournamentTeam                 $homeTeam
 * @property-read Collection|GroupGameRegularPlayer[] $protocols
 * @property-read GroupTournament                     $tournament
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupGameRegular newModelQuery()
 * @method static EloquentBuilder|GroupGameRegular newQuery()
 * @method static QueryBuilder|GroupGameRegular onlyTrashed()
 * @method static EloquentBuilder|GroupGameRegular query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupGameRegular whereAwayAttackTime($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayFaceoff($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayHit($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayPassPercent($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayPenaltySuccess($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayPenaltyTime($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayPenaltyTotal($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayPowerplayTime($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayScore($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayShorthandedGoal($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayShot($value)
 * @method static EloquentBuilder|GroupGameRegular whereAwayTeamId($value)
 * @method static EloquentBuilder|GroupGameRegular whereCreatedAt($value)
 * @method static EloquentBuilder|GroupGameRegular whereDeletedAt($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomeAttackTime($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomeFaceoff($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomeHit($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomePassPercent($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomePenaltySuccess($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomePenaltyTime($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomePenaltyTotal($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomePowerplayTime($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomeScore($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomeShorthandedGoal($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomeShot($value)
 * @method static EloquentBuilder|GroupGameRegular whereHomeTeamId($value)
 * @method static EloquentBuilder|GroupGameRegular whereId($value)
 * @method static EloquentBuilder|GroupGameRegular whereIsOvertime($value)
 * @method static EloquentBuilder|GroupGameRegular whereIsShootout($value)
 * @method static EloquentBuilder|GroupGameRegular whereIsTechnicalDefeat($value)
 * @method static EloquentBuilder|GroupGameRegular whereMatchId($value)
 * @method static EloquentBuilder|GroupGameRegular wherePlayedAt($value)
 * @method static EloquentBuilder|GroupGameRegular whereRound($value)
 * @method static EloquentBuilder|GroupGameRegular whereTournamentId($value)
 * @method static EloquentBuilder|GroupGameRegular whereUpdatedAt($value)
 * @method static QueryBuilder|GroupGameRegular withTrashed()
 * @method static QueryBuilder|GroupGameRegular withoutTrashed()
 * @mixin Eloquent
 */
class GroupGameRegular extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupGameRegular';

    /**
     * @var GroupGameRegularPlayer[]
     */
    public $homeProtocols = [];
    /**
     * @var GroupGameRegularPlayer[]
     */
    public $awayProtocols = [];
    /**
     * @var null|GroupGameRegularPlayer
     */
    public $homeGoalie = null;
    /**
     * @var null|GroupGameRegularPlayer
     */
    public $awayGoalie = null;

    /**
     * @var array
     */
    protected $fillable = [
        'tournament_id',
        'home_team_id',
        'away_team_id',
        'round',
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
        'isShootout',
        'isTechnicalDefeat',
        //'createdAt',
        'playedAt',
        //'deletedAt',
        'match_id',
    ];

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
     * @return BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\GroupTournament', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function protocols()
    {
        return $this->hasMany('App\Models\GroupGameRegularPlayer', 'game_id');
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
            $players['home'][] = $player->player->getSafeData();
        }
        foreach ($this->awayTeam->team->players as $player) {
            $players['away'][] = $player->player->getSafeData();
        }
        return $players;
    }

    /**
     * @return Collection
     */
    public function getStars() {
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
