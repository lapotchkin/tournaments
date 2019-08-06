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
 * @property GroupGamePlayoff[]       $groupGamePlayoffs
 * @property GroupGamePlayoffPlayer[] $groupGamePlayoffPlayers
 * @property GroupGameRegular[]       $groupGameRegulars
 * @property GroupGameRegularPlayer[] $groupGameRegularPlayers
 * @property GroupTournamentPlayoff[] $groupTournamentPlayoffs
 * @property GroupTournamentTeam[]    $groupTournamentTeams
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
    public function groupGamePlayoffs()
    {
        return $this->hasMany('App\Models\GroupGamePlayoff', 'home_team_id')
            ->where('home_team_id', '=', 'id')
            ->orWhere('away_team_id', '=', 'id');
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
    public function groupGameRegulars()
    {
        return $this->hasMany('App\Models\GroupGameRegular', 'home_team_id')
            ->where('home_team_id', '=', 'id')
            ->orWhere('away_team_id', '=', 'id');
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
    public function groupTournamentPlayoffs()
    {
        return $this->hasMany('App\Models\GroupTournamentPlayoff', 'team_one_id')
            ->where('team_one_id', '=', 'id')
            ->orWhere('team_two_id', '=', 'id');
    }

    /**
     * @return HasMany
     */
    public function groupTournamentTeams()
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
