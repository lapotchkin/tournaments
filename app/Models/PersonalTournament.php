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
 * App\Models\PersonalTournament
 *
 * @property int                                        $id               ID
 * @property string                                     $platform_id      ID платформы
 * @property string                                     $app_id           ID игры
 * @property string|null                                $league_id        ID лиги
 * @property string                                     $title            Название
 * @property int|null                                   $playoff_rounds   Количество раундов плейоф
 * @property Carbon                                     $createdAt        Дата создания
 * @property Carbon|null                                $deletedAt        Дата удаления
 * @property int                                        $thirdPlaceSeries Серия за третье место
 * @property-read App                                   $app
 * @property-read League|null                           $league
 * @property-read Collection|PersonalGameRegular[]      $regularGames
 * @property-read Collection|PersonalTournamentPlayer[] $tournamentPlayers
 * @property-read Collection|PersonalTournamentWinner[] $winners
 * @property-read Platform                              $platform
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|PersonalTournament newModelQuery()
 * @method static EloquentBuilder|PersonalTournament newQuery()
 * @method static QueryBuilder|PersonalTournament onlyTrashed()
 * @method static EloquentBuilder|PersonalTournament query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|PersonalTournament whereAppId($value)
 * @method static EloquentBuilder|PersonalTournament whereCreatedAt($value)
 * @method static EloquentBuilder|PersonalTournament whereDeletedAt($value)
 * @method static EloquentBuilder|PersonalTournament whereId($value)
 * @method static EloquentBuilder|PersonalTournament whereLeagueId($value)
 * @method static EloquentBuilder|PersonalTournament wherePlatformId($value)
 * @method static EloquentBuilder|PersonalTournament wherePlayoffRounds($value)
 * @method static EloquentBuilder|PersonalTournament whereTitle($value)
 * @method static QueryBuilder|PersonalTournament withTrashed()
 * @method static QueryBuilder|PersonalTournament withoutTrashed()
 * @mixin Eloquent
 */
class PersonalTournament extends Model
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
    protected $table = 'personalTournament';

    /**
     * @var array
     */
    protected $fillable = [
        'platform_id',
        'app_id',
        'league_id',
        'title',
        'playoff_rounds',
        'createdAt',
        'deletedAt',
        'thirdPlaceSeries',
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
    public function league()
    {
        return $this->belongsTo('App\Models\League');
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
        return $this->hasMany('App\Models\PersonalGameRegular', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function playoff()
    {
        return $this->hasMany('App\Models\PersonalTournamentPlayoff', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function tournamentPlayers()
    {
        return $this->hasMany('App\Models\PersonalTournamentPlayer', 'tournament_id');
    }

    /**
     * @return HasManyThrough
     */
    public function players()
    {
        return $this->hasManyThrough(
            'App\Models\Player',
            'App\Models\PersonalTournamentPlayer',
            'tournament_id',
            'id',
            'id',
            'player_id'
        )
            ->orderBy('player.tag');
    }

    /**
     * @return HasMany
     */
    public function winners()
    {
        return $this->hasMany('App\Models\PersonalTournamentWinner', 'tournament_id');
    }
}
