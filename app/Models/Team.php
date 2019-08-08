<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Team
 * @package App\Models
 * @property int                      $id
 * @property string                   $platform_id
 * @property string                   $name
 * @property string                   $createdAt
 * @property string                   $deletedAt
 * @property Platform                 $platform
 * @property GroupGamePlayoff[]       $gamePlayoffs
 * @property GroupGamePlayoffPlayer[] $gamePlayoffPlayers
 * @property GroupGameRegular[]       $gameRegulars
 * @property GroupGameRegularPlayer[] $gameRegularPlayers
 * @property GroupTournamentPlayoff[] $tournamentPlayoffs
 * @property GroupTournamentTeam[]    $tournamentTeams
 * @property TeamPlayer[]             $teamPlayers
 */
class Team extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
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
    protected $fillable = ['platform_id', 'name', 'createdAt', 'deletedAt'];

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
    public function gamePlayoffs()
    {
        return $this->hasMany('App\Models\GroupGamePlayoff', 'home_team_id')
            ->where('home_team_id', '=', 'id')
            ->orWhere('away_team_id', '=', 'id');
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
    public function gameRegulars()
    {
        return $this->hasMany('App\Models\GroupGameRegular', 'home_team_id')
            ->where('home_team_id', '=', 'id')
            ->orWhere('away_team_id', '=', 'id');
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
    public function tournamentPlayoffs()
    {
        return $this->hasMany('App\Models\GroupTournamentPlayoff', 'team_one_id')
            ->where('team_one_id', '=', 'id')
            ->orWhere('team_two_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function tournamentTeams()
    {
        return $this->hasMany('App\Models\GroupTournamentTeam');
    }

    /**
     * @return HasMany
     */
    public function teamPlayers()
    {
        return $this->hasMany('App\Models\TeamPlayer');
    }
}
