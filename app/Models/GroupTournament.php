<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property GroupGameRegular[]       $groupGameRegulars
 * @property GroupTournamentPlayoff[] $groupTournamentPlayoffs
 * @property GroupTournamentTeam[]    $groupTournamentTeams
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
    public function groupGameRegulars()
    {
        return $this->hasMany('App\Models\GroupGameRegular', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function groupTournamentPlayoffs()
    {
        return $this->hasMany('App\Models\GroupTournamentPlayoff', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function groupTournamentTeams()
    {
        return $this->hasMany('App\Models\GroupTournamentTeam', 'tournament_id');
    }
}
