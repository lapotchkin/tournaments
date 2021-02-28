<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupTournamentTeam
 *
 * @property int                  $tournament_id ID турнира
 * @property int                  $team_id       ID команды
 * @property int|null             $division      Группа
 * @property Carbon               $createdAt     Дата создания
 * @property Carbon|null          $deletedAt     Дата удаления
 * @property-read Team            $team
 * @property-read GroupTournament $tournament
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupTournamentTeam newModelQuery()
 * @method static EloquentBuilder|GroupTournamentTeam newQuery()
 * @method static QueryBuilder|GroupTournamentTeam onlyTrashed()
 * @method static EloquentBuilder|GroupTournamentTeam query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupTournamentTeam whereCreatedAt($value)
 * @method static EloquentBuilder|GroupTournamentTeam whereDeletedAt($value)
 * @method static EloquentBuilder|GroupTournamentTeam whereDivision($value)
 * @method static EloquentBuilder|GroupTournamentTeam whereTeamId($value)
 * @method static EloquentBuilder|GroupTournamentTeam whereTournamentId($value)
 * @method static QueryBuilder|GroupTournamentTeam withTrashed()
 * @method static QueryBuilder|GroupTournamentTeam withoutTrashed()
 * @mixin Eloquent
 */
class GroupTournamentTeam extends Model
{
    use SoftDeletes;
    use Compoships;

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
