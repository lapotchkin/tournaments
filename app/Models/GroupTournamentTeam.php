<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GroupTournamentTeam
 * @package App\Models
 * @property int             $tournament_id
 * @property int             $team_id
 * @property boolean         $division
 * @property string          $createdAt
 * @property string          $deletedAt
 * @property Team            $team
 * @property GroupTournament $tournament
 */
class GroupTournamentTeam extends Model
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
    protected $table = 'groupTournament_team';

    /**
     * @var array
     */
    protected $fillable = ['division', 'createdAt', 'deletedAt'];

    /**
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * @return BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\GroupTournament', 'tournament_id');
    }
}
