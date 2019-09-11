<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                $id
 * @property int                $tournament_id
 * @property int                $player_id
 * @property int                $place
 * @property string             $createdAt
 * @property string             $deletedAt
 * @property Player             $player
 * @property PersonalTournament $tournament
 */
class PersonalTournamentWinner extends Model
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
    protected $table = 'personalTournamentWinner';

    /**
     * @var array
     */
    protected $fillable = ['tournament_id', 'player_id', 'place'];

    /**
     * @return BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    /**
     * @return BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\PersonalTournament', 'tournament_id');
    }
}
