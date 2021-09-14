<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * @property int                $id
 * @property int                $tournament_id
 * @property int                $player_id
 * @property int                $place
 * @property string             $createdAt
 * @property string             $deletedAt
 * @property Player             $player
 * @property PersonalTournament $tournament
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|PersonalTournament newModelQuery()
 * @method static EloquentBuilder|PersonalTournament newQuery()
 * @method static QueryBuilder|PersonalTournament onlyTrashed()
 * @method static EloquentBuilder|PersonalTournament query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|PersonalTournament whereCreatedAt($value)
 * @method static EloquentBuilder|PersonalTournament whereDeletedAt($value)
 * @method static EloquentBuilder|PersonalTournament whereId($value)
 * @method static EloquentBuilder|PersonalTournament wherePlace($value)
 * @method static EloquentBuilder|PersonalTournament wherePlayerId($value)
 * @method static EloquentBuilder|PersonalTournament whereTournamentId($value)
 * @method static QueryBuilder|PersonalTournament withTrashed()
 * @method static QueryBuilder|PersonalTournament withoutTrashed()
 * @mixin Eloquent
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
