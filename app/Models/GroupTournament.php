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
 * @property int                                      $id             ID
 * @property string                                   $platform_id    ID игровой платформы
 * @property string                                   $app_id         ID игры
 * @property string                                   $title          Название
 * @property int|null                                 $playoff_rounds Количество раундов плейоф
 * @property int|null                                 $min_players    Минимальное количество игроков в команде
 * @property Carbon                                   $createdAt      Дата создания
 * @property Carbon|null                              $deletedAt      Дата удаления
 * @property-read App                                 $app
 * @property-read Platform                            $platform
 * @property-read Collection|GroupGameRegular[]       $regularGames
 * @property-read Collection|Team[]                   $teams
 * @property-read Collection|GroupTournamentPlayoff[] $tournamentPlayoffs
 * @property-read Collection|GroupTournamentTeam[]    $tournamentTeams
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
    protected $fillable = ['platform_id', 'app_id', 'title', 'playoff_rounds', 'min_players', 'createdAt', 'deletedAt'];

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
        return $this->hasMany('App\Models\GroupGameRegular', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function tournamentPlayoffs()
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
}
