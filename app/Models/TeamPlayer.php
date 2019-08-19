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
 * App\Models\TeamPlayer
 *
 * @property int         $team_id   ID команды
 * @property int         $player_id ID игрока
 * @property int         $isCaptain Капитан
 * @property Carbon      $createdAt Дата создания
 * @property Carbon|null $deletedAt Дата удаления
 * @property-read Player $player
 * @property-read Team   $team
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|TeamPlayer newModelQuery()
 * @method static EloquentBuilder|TeamPlayer newQuery()
 * @method static QueryBuilder|TeamPlayer onlyTrashed()
 * @method static EloquentBuilder|TeamPlayer query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|TeamPlayer whereCreatedAt($value)
 * @method static EloquentBuilder|TeamPlayer whereDeletedAt($value)
 * @method static EloquentBuilder|TeamPlayer whereIsCaptain($value)
 * @method static EloquentBuilder|TeamPlayer wherePlayerId($value)
 * @method static EloquentBuilder|TeamPlayer whereTeamId($value)
 * @method static QueryBuilder|TeamPlayer withTrashed()
 * @method static QueryBuilder|TeamPlayer withoutTrashed()
 * @mixin Eloquent
 */
class TeamPlayer extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_player';

    /**
     * @var array
     */
    protected $fillable = ['isCaptain', 'createdAt', 'deletedAt'];

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
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
