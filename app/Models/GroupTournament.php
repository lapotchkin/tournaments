<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupTournament
 *
 * @property int                                      $id               ID
 * @property string                                   $platform_id      ID игровой платформы
 * @property string                                   $app_id           ID игры
 * @property string                                   $title            Название
 * @property int|null                                 $playoff_rounds   Количество раундов плейоф
 * @property int|null                                 $playoff_limit    Ручной лимит количества участников плей-офф
 * @property int|null                                 $min_players      Минимальное количество игроков в команде
 * @property Carbon                                   $createdAt        Дата создания
 * @property Carbon|null                              $deletedAt        Дата удаления
 * @property int                                      $thirdPlaceSeries Серия за третье место
 * @property int                                      $vk_group_id      Группа Турнира в ВК
 * @property Carbon                                   $startedAt        Дата начала турнира
 * @property-read App                                 $app
 * @property-read Platform                            $platform
 * @property-read Collection|GroupGameRegular[]       $regularGames
 * @property-read Collection|Team[]                   $teams
 * @property-read Collection|GroupTournamentPlayoff[] $playoff
 * @property-read Collection|GroupTournamentTeam[]    $tournamentTeams
 * @property-read Collection|GroupTournamentWinner[]  $winners
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupTournament newModelQuery()
 * @method static EloquentBuilder|GroupTournament newQuery()
 * @method static QueryBuilder|GroupTournament onlyTrashed()
 * @method static EloquentBuilder|GroupTournament query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupTournament whereAppId($value)
 * @method static EloquentBuilder|GroupTournament whereCreatedAt($value)
 * @method static EloquentBuilder|GroupTournament whereDeletedAt($value)
 * @method static EloquentBuilder|GroupTournament whereId($value)
 * @method static EloquentBuilder|GroupTournament whereMinPlayers($value)
 * @method static EloquentBuilder|GroupTournament wherePlatformId($value)
 * @method static EloquentBuilder|GroupTournament wherePlayoffRounds($value)
 * @method static EloquentBuilder|GroupTournament wherePlayoffLimit($value)
 * @method static EloquentBuilder|GroupTournament whereTitle($value)
 * @method static QueryBuilder|GroupTournament withTrashed()
 * @method static QueryBuilder|GroupTournament withoutTrashed()
 * @mixin Eloquent
 */
class GroupTournament extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupTournament';

    /**
     * @var array
     */
    protected $fillable = [
        'platform_id',
        'app_id',
        'title',
        'playoff_rounds',
        'playoff_limit',
        'min_players',
        'createdAt',
        'deletedAt',
        'thirdPlaceSeries',
        'vk_group_id',
        'startedAt',
    ];

    protected $casts = [
        'startedAt' => 'date',
    ];

    /**
     * @return BelongsTo
     */
    public function app()
    {
        return $this->belongsTo('App\Models\App');
    }

    /**
     * @return BelongsTo
     */
    public function platform()
    {
        return $this->belongsTo('App\Models\Platform');
    }

    /**
     * @return HasMany
     */
    public function regularGames()
    {
        return $this->hasMany('App\Models\GroupGameRegular', 'tournament_id')
            ->orderBy('round')
            ->orderBy('id');
    }

    /**
     * @return Collection|GroupGameRegular[]
     */
    public function getNotSharedRegularGames()
    {
        return $this->hasMany('App\Models\GroupGameRegular', 'tournament_id')
            ->whereNotNull('home_score')
            ->whereNotNull('away_score')
            ->whereNull('sharedAt')
            ->whereNotNull('playedAt')
            ->orderBy('round')
            ->get();
    }

    /**
     * @return Collection|GroupGamePlayoff[]
     */
    public function getNotSharedPlayoffGames()
    {
        return $this->hasManyThrough(
            'App\Models\GroupGamePlayoff',
            'App\Models\GroupTournamentPlayoff',
            'tournament_id',
            'playoff_pair_id',
            'id',
            'id'
        )
            ->whereNull('sharedAt')
            ->whereNotNull('home_score')
            ->whereNotNull('away_score')
            ->whereNotNull('playedAt')
            ->orderBy('round')
            ->get();
    }

    /**
     * @return HasMany
     */
    public function playoff()
    {
        return $this->hasMany('App\Models\GroupTournamentPlayoff', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function tournamentTeams()
    {
        return $this->hasMany('App\Models\GroupTournamentTeam', 'tournament_id');
    }

    /**
     * @return HasManyThrough
     */
    public function teams()
    {
        return $this->hasManyThrough(
            'App\Models\Team',
            'App\Models\GroupTournamentTeam',
            'tournament_id',
            'id',
            'id',
            'team_id'
        )
            ->orderBy('team.name');
    }

    /**
     * @return HasMany
     */
    public function winners()
    {
        return $this->hasMany('App\Models\GroupTournamentWinner', 'tournament_id');
    }
}
