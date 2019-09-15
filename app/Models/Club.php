<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Club
 *
 * @property string                                     $id        ID
 * @property string                                     $league_id ID лиги
 * @property string                                     $title     Название
 * @property Carbon                                     $createdAt Дата создания
 * @property Carbon|null                                $deletedAt Дата удаления
 * @property-read League                                $league
 * @property-read Collection|PersonalTournamentPlayer[] $tournamentPlayers
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|Club newModelQuery()
 * @method static EloquentBuilder|Club newQuery()
 * @method static QueryBuilder|Club onlyTrashed()
 * @method static EloquentBuilder|Club query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|Club whereCreatedAt($value)
 * @method static EloquentBuilder|Club whereDeletedAt($value)
 * @method static EloquentBuilder|Club whereId($value)
 * @method static EloquentBuilder|Club whereLeagueId($value)
 * @method static EloquentBuilder|Club whereTitle($value)
 * @method static QueryBuilder|Club withTrashed()
 * @method static QueryBuilder|Club withoutTrashed()
 * @mixin Eloquent
 */
class Club extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'club';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var array
     */
    protected $fillable = ['title', 'createdAt', 'deletedAt'];

    /**
     * @return BelongsTo
     */
    public function league()
    {
        return $this->belongsTo('App\Models\League');
    }

    /**
     * @return HasMany
     */
    public function tournamentPlayers()
    {
        return $this->hasMany('App\Models\PersonalTournamentPlayer');
    }
}
