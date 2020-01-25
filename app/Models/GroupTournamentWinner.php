<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupTournamentWinner
 *
 * @property int                  $id            ID
 * @property int                  $tournament_id ID турнира
 * @property int                  $team_id       ID команды
 * @property int                  $place         Место
 * @property Carbon               $createdAt
 * @property Carbon|null          $deletedAt
 * @property-read Team            $team
 * @property-read GroupTournament $tournament
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupTournamentWinner newModelQuery()
 * @method static EloquentBuilder|GroupTournamentWinner newQuery()
 * @method static QueryBuilder|GroupTournamentWinner onlyTrashed()
 * @method static EloquentBuilder|GroupTournamentWinner query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupTournamentWinner whereCreatedAt($value)
 * @method static EloquentBuilder|GroupTournamentWinner whereDeletedAt($value)
 * @method static EloquentBuilder|GroupTournamentWinner whereId($value)
 * @method static EloquentBuilder|GroupTournamentWinner wherePlace($value)
 * @method static EloquentBuilder|GroupTournamentWinner whereTeamId($value)
 * @method static EloquentBuilder|GroupTournamentWinner whereTournamentId($value)
 * @method static QueryBuilder|GroupTournamentWinner withTrashed()
 * @method static QueryBuilder|GroupTournamentWinner withoutTrashed()
 * @mixin Eloquent
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
