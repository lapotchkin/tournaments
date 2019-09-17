<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\Player
 *
 * @property int                                                        $id          ID
 * @property string                                                     $tag         Игровой тэг
 * @property string                                                     $name        Имя
 * @property int|null                                                   $role        Роль пользователя в системе
 * @property string|null                                                $vk          VK
 * @property string|null                                                $city        Город
 * @property float|null                                                 $lat         Широта
 * @property float|null                                                 $lon         Долгота
 * @property string|null                                                $platform_id ID платфоррмы
 * @property Carbon                                                     $createdAt   Дата создания
 * @property Carbon|null                                                $deletedAt   Дата удаления
 * @property-read Collection|GroupGamePlayoffPlayer[]                   $gamePlayoffPlayers
 * @property-read Collection|GroupGameRegularPlayer[]                   $gameRegularPlayers
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read Collection|PersonalGamePlayoff[]                      $personalPlayoffGames
 * @property-read Collection|PersonalGameRegular[]                      $personalRegularGames
 * @property-read Collection|PersonalTournamentPlayer[]                 $personalTournamentPlayers
 * @property-read Collection|PersonalTournamentPlayoff[]                $personalTournamentPlayoffs
 * @property-read Platform|null                                         $platform
 * @property-read Collection|TeamPlayer[]                               $teamPlayers
 * @property-read Collection|PersonalTournamentWinner[]                 $tournamentWins
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|Player newModelQuery()
 * @method static EloquentBuilder|Player newQuery()
 * @method static QueryBuilder|Player onlyTrashed()
 * @method static EloquentBuilder|Player query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|Player whereCity($value)
 * @method static EloquentBuilder|Player whereCreatedAt($value)
 * @method static EloquentBuilder|Player whereDeletedAt($value)
 * @method static EloquentBuilder|Player whereId($value)
 * @method static EloquentBuilder|Player whereLat($value)
 * @method static EloquentBuilder|Player whereLon($value)
 * @method static EloquentBuilder|Player whereName($value)
 * @method static EloquentBuilder|Player wherePlatformId($value)
 * @method static EloquentBuilder|Player whereRole($value)
 * @method static EloquentBuilder|Player whereTag($value)
 * @method static EloquentBuilder|Player whereVk($value)
 * @method static QueryBuilder|Player withTrashed()
 * @method static QueryBuilder|Player withoutTrashed()
 * @mixin Eloquent
 */
class Player extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player';

    /**
     * @var array
     */
    protected $fillable = ['platform_id', 'tag', 'name', 'vk', 'city', 'lat', 'lon', 'createdAt', 'deletedAt'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function platform()
    {
        return $this->belongsTo('App\Models\Platform');
    }

    /**
     * @return HasMany
     */
    public function gamePlayoffPlayers()
    {
        return $this->hasMany('App\Models\GroupGamePlayoffPlayer');
    }

    /**
     * @return HasMany
     */
    public function gameRegularPlayers()
    {
        return $this->hasMany('App\Models\GroupGameRegularPlayer');
    }

    /**
     * @return HasMany
     */
    public function personalPlayoffGames()
    {
        return $this->hasMany('App\Models\PersonalGamePlayoff')
            ->where('home_player_id', '=', 'id')
            ->orWhere('away_player_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function personalRegularGames()
    {
        return $this->hasMany('App\Models\PersonalGameRegular', 'home_player_id')
            ->where('home_player_id', '=', 'id')
            ->orWhere('away_player_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function personalTournamentPlayoffs()
    {
        return $this->hasMany('App\Models\PersonalTournamentPlayoff', 'player_one_id')
            ->where('player_one_id', '=', 'id')
            ->orWhere('player_two_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function personalTournamentPlayers()
    {
        return $this->hasMany('App\Models\PersonalTournamentPlayer');
    }

    /**
     * @return HasMany
     */
    public function teamPlayers()
    {
        return $this->hasMany('App\Models\TeamPlayer');
    }

    /**
     * @return HasMany
     */
    public function tournamentWins()
    {
        return $this->hasMany('App\Models\PersonalTournamentWinner');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 1;
    }

    /**
     * @return object
     */
    public function getSafeData()
    {
        return (object)[
            'id'   => $this->id,
            'tag'  => $this->tag,
            'name' => $this->name,
        ];
    }

    /**
     * @param int $tournamentId
     * @return string|null
     */
    public function getClubId(int $tournamentId)
    {
        foreach ($this->personalTournamentPlayers as $competitor) {
            if ($competitor->tournament_id === $tournamentId) {
                return $competitor->club_id;
            }
        }
        return null;
    }

    /**
     * @param int $tournamentId
     * @return string|null
     */
    public function getDivision(int $tournamentId)
    {
        foreach ($this->personalTournamentPlayers as $competitor) {
            if ($competitor->tournament_id === $tournamentId) {
                return $competitor->division;
            }
        }
        return null;
    }
}
