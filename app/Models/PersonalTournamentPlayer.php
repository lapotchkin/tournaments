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
 * App\Models\PersonalTournamentPlayer
 *
 * @property int                     $tournament_id ID турнира
 * @property int                     $player_id     ID игрока
 * @property string|null             $club_id       Клуб
 * @property int|null                $division      Группа
 * @property Carbon                  $createdAt     Дата создания
 * @property Carbon|null             $deletedAt     Дата удаления
 * @property-read Club|null          $club
 * @property-read PersonalTournament $tournament
 * @property-read Player             $player
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|PersonalTournamentPlayer newModelQuery()
 * @method static EloquentBuilder|PersonalTournamentPlayer newQuery()
 * @method static QueryBuilder|PersonalTournamentPlayer onlyTrashed()
 * @method static EloquentBuilder|PersonalTournamentPlayer query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|PersonalTournamentPlayer whereClubId($value)
 * @method static EloquentBuilder|PersonalTournamentPlayer whereCreatedAt($value)
 * @method static EloquentBuilder|PersonalTournamentPlayer whereDeletedAt($value)
 * @method static EloquentBuilder|PersonalTournamentPlayer whereDivision($value)
 * @method static EloquentBuilder|PersonalTournamentPlayer wherePlayerId($value)
 * @method static EloquentBuilder|PersonalTournamentPlayer whereTournamentId($value)
 * @method static QueryBuilder|PersonalTournamentPlayer withTrashed()
 * @method static QueryBuilder|PersonalTournamentPlayer withoutTrashed()
 * @mixin Eloquent
 */
class PersonalTournamentPlayer extends Model
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
    public function tournament()
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
