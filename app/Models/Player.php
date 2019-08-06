<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Player
 * @package App\Models
 * @property int                         $id
 * @property string                      $platform_id
 * @property string                      $tag
 * @property string                      $name
 * @property string                      $vk
 * @property string                      $city
 * @property float                       $lat
 * @property float                       $lon
 * @property string                      $createdAt
 * @property string                      $deletedAt
 * @property Platform                    $platform
 * @property GroupGamePlayoffPlayer[]    $groupGamePlayoffPlayers
 * @property GroupGameRegularPlayer[]    $groupGameRegularPlayers
 * @property PersonalGamePlayoff[]       $personalGamePlayoffs
 * @property PersonalGameRegular[]       $personalGameRegulars
 * @property PersonalTournamentPlayoff[] $personalTournamentPlayoffs
 * @property PersonalTournamentPlayer[]  $personalTournamentPlayers
 * @property TeamPlayer[]                $teamPlayers
 */
class Player extends Model
{
    use SoftDeletes;

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
    public function groupGamePlayoffPlayers()
    {
        return $this->hasMany('App\Models\GroupGamePlayoffPlayer');
    }

    /**
     * @return HasMany
     */
    public function groupGameRegularPlayers()
    {
        return $this->hasMany('App\Models\GroupGameRegularPlayer');
    }

    /**
     * @return HasMany
     */
    public function personalGamePlayoffs()
    {
        return $this->hasMany('App\Models\PersonalGamePlayoff')
            ->where('home_player_id', '=', 'id')
            ->orWhere('away_player_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function personalGameRegulars()
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
}
