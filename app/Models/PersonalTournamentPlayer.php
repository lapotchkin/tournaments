<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PersonalTournamentPlayer
 * @package App\Models
 * @property int                $tournament_id
 * @property int                $player_id
 * @property string             $club_id
 * @property boolean            $division
 * @property string             $createdAt
 * @property string             $deletedAt
 * @property Club               $club
 * @property PersonalTournament $personalTournament
 * @property Player             $player
 */
class PersonalTournamentPlayer extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personalTournament_player';

    /**
     * @var array
     */
    protected $fillable = ['club_id', 'division', 'createdAt', 'deletedAt'];

    /**
     * @return BelongsTo
     */
    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }

    /**
     * @return BelongsTo
     */
    public function personalTournament()
    {
        return $this->belongsTo('App\Models\PersonalTournament', 'tournament_id');
    }

    /**
     * @return BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }
}
