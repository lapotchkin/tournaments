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
 * App\Models\Team
 *
 * @property int                                      $id          ID
 * @property string|null                              $platform_id ID платформы
 * @property string                                   $name        Название
 * @property Carbon                                   $createdAt   Дата создания
 * @property Carbon|null                              $deletedAt   Дата удаления
 * @property string|null                              $short_name  Краткое название команды
 * @property-read Collection|GroupGamePlayoffPlayer[] $playoffGamesPlayers
 * @property-read Collection|GroupGamePlayoff[]       $playoffGames
 * @property-read Collection|GroupGameRegularPlayer[] $regularGamesPlayers
 * @property-read Collection|GroupGameRegular[]       $regularGames
 * @property-read Platform|null                       $platform
 * @property-read Collection|TeamPlayer[]             $teamPlayers
 * @property-read Collection|Player[]                 $players
 * @property-read Collection|GroupTournamentPlayoff[] $tournamentPlayoffs
 * @property-read Collection|GroupTournamentTeam[]    $teamTournaments
 * @property-read Collection|GroupTournament[]        $tournaments
 * @property-read Collection|AppTeam[]                $teamApps
 * @property-read Collection|GroupTournamentWinner[]  $tournamentWins
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|Team newModelQuery()
 * @method static EloquentBuilder|Team newQuery()
 * @method static QueryBuilder|Team onlyTrashed()
 * @method static EloquentBuilder|Team query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|Team whereCreatedAt($value)
 * @method static EloquentBuilder|Team whereDeletedAt($value)
 * @method static EloquentBuilder|Team whereId($value)
 * @method static EloquentBuilder|Team whereName($value)
 * @method static EloquentBuilder|Team wherePlatformId($value)
 * @method static EloquentBuilder|Team whereShortName($value)
 * @method static QueryBuilder|Team withTrashed()
 * @method static QueryBuilder|Team withoutTrashed()
 * @mixin Eloquent
 */
class Team extends Model
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
    protected $table = 'team';

    /**
     * @var array
     */
    protected $fillable = ['platform_id', 'name', 'short_name', 'createdAt', 'deletedAt'];

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
    public function playoffGames()
    {
        return $this->hasMany('App\Models\GroupGamePlayoff', 'home_team_id')
            ->where('home_team_id', '=', 'id')
            ->orWhere('away_team_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function playoffGamesPlayers()
    {
        return $this->hasMany('App\Models\GroupGamePlayoffPlayer');
    }

    /**
     * @return HasMany
     */
    public function regularGames()
    {
        return $this->hasMany('App\Models\GroupGameRegular', 'home_team_id')
            ->where('home_team_id', '=', 'id')
            ->orWhere('away_team_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function regularGamesPlayers()
    {
        return $this->hasMany('App\Models\GroupGameRegularPlayer');
    }

    /**
     * @return HasMany
     */
    public function tournamentPlayoffs()
    {
        return $this->hasMany('App\Models\GroupTournamentPlayoff', 'team_one_id')
            ->where('team_one_id', '=', 'id')
            ->orWhere('team_two_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function teamTournaments()
    {
        return $this->hasMany('App\Models\GroupTournamentTeam');
    }

    /**
     * @return HasManyThrough
     */
    public function tournaments()
    {
        return $this->hasManyThrough(
            'App\Models\GroupTournament',
            'App\Models\GroupTournamentTeam',
            'team_id',
            'id',
            'id',
            'tournament_id'
        )
            ->orderBy('groupTournament.id');
    }

    /**
     * @return HasMany
     */
    public function teamPlayers()
    {
        return $this->hasMany('App\Models\TeamPlayer');
    }

    /**
     * @return HasManyThrough
     */
    public function players()
    {
        return $this->hasManyThrough(
            'App\Models\Player',
            'App\Models\TeamPlayer',
            'team_id',
            'id',
            'id',
            'player_id'
        )
            ->orderBy('player.name');
    }

    /**
     * @return HasMany
     */
    public function teamApps()
    {
        return $this->hasMany('App\Models\AppTeam');
    }

    /**
     * @param string $appId
     * @return int|null
     */
    public function getClubId(string $appId)
    {
        $teamApp = $this->teamApps
            ->where('app_id', '=', $appId)
            ->where('team_id', '=', $this->id)
            ->first();
        return is_null($teamApp) ? null : $teamApp->app_team_id;
    }

    /**
     * @return HasMany
     */
    public function tournamentWins()
    {
        return $this->hasMany('App\Models\GroupTournamentWinner');
    }
}
