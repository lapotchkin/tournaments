<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int             $id
 * @property int             $tournament_id
 * @property int             $team_id
 * @property int             $place
 * @property string          $createdAt
 * @property string          $deletedAt
 * @property Team            $team
 * @property GroupTournament $tournament
 */
class GroupTournamentWinner extends Model
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
    protected $table = 'groupTournamentWinner';

    /**
     * @var array
     */
    protected $fillable = ['tournament_id', 'team_id', 'place'];

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
