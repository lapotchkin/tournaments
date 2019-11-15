<?php


namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\TeamManagement
 *
 * @property int                       $id
 * @property int                       $team_id    ID команды
 * @property int                       $manager_id ID игрока совершившего действие
 * @property int                       $player_id  ID игрока
 * @property int                       $action_id  Действие
 * @property Carbon                    $createdAt
 * @property Carbon|null               $deletedAt
 * @property-read TeamManagementAction $action
 * @property-read Player               $manager
 * @property-read Player               $player
 * @property-read Team                 $team
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|TeamManagement newModelQuery()
 * @method static EloquentBuilder|TeamManagement newQuery()
 * @method static QueryBuilder|TeamManagement onlyTrashed()
 * @method static EloquentBuilder|TeamManagement query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|TeamManagement whereActionId($value)
 * @method static EloquentBuilder|TeamManagement whereCreatedAt($value)
 * @method static EloquentBuilder|TeamManagement whereDeletedAt($value)
 * @method static EloquentBuilder|TeamManagement whereId($value)
 * @method static EloquentBuilder|TeamManagement whereManagerId($value)
 * @method static EloquentBuilder|TeamManagement wherePlayerId($value)
 * @method static EloquentBuilder|TeamManagement whereTeamId($value)
 * @method static QueryBuilder|TeamManagement withTrashed()
 * @method static QueryBuilder|TeamManagement withoutTrashed()
 * @mixin Eloquent
 */
class TeamManagement extends Model
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
    protected $table = 'teamManagement';

    /**
     * @var array
     */
    protected $fillable = ['title', 'team_id', 'manager_id', 'player_id', 'action_id'];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    /**
     * @return HasOne
     */
    public function team()
    {
        return $this->hasOne('App\Models\Team', 'id', 'team_id');
    }

    /**
     * @return HasOne
     */
    public function manager()
    {
        return $this->hasOne('App\Models\Player', 'id', 'manager_id');
    }

    /**
     * @return HasOne
     */
    public function player()
    {
        return $this->hasOne('App\Models\Player', 'id', 'player_id');
    }

    /**
     * @return HasOne
     */
    public function action()
    {
        return $this->hasOne('App\Models\TeamManagementAction', 'id', 'action_id');
    }
}
