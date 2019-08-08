<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GroupTournament
 * @package App\Models
 * @property int                      $id
 * @property string                   $platform_id
 * @property string                   $app_id
 * @property string                   $title
 * @property boolean                  $playoff_rounds
 * @property boolean                  $min_players
 * @property string                   $createdAt
 * @property string                   $deletedAt
 * @property App                      $app
 * @property Platform                 $platform
 * @property GroupGameRegular[]       $regularGames
 * @property GroupTournamentPlayoff[] $tournamentPlayoffs
 * @property GroupTournamentTeam[]    $tournamentTeams
 * @property Team[]                   $teams
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
