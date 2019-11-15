<?php


namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\TeamManagementAction
 *
 * @property int         $id
 * @property string      $title Название
 * @property Carbon      $createdAt
 * @property Carbon|null $deletedAt
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|TeamManagementAction newModelQuery()
 * @method static EloquentBuilder|TeamManagementAction newQuery()
 * @method static QueryBuilder|TeamManagementAction onlyTrashed()
 * @method static EloquentBuilder|TeamManagementAction query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|TeamManagementAction whereCreatedAt($value)
 * @method static EloquentBuilder|TeamManagementAction whereDeletedAt($value)
 * @method static EloquentBuilder|TeamManagementAction whereId($value)
 * @method static EloquentBuilder|TeamManagementAction whereTitle($value)
 * @method static QueryBuilder|TeamManagementAction withTrashed()
 * @method static QueryBuilder|TeamManagementAction withoutTrashed()
 * @mixin Eloquent
 */
class TeamManagementAction extends Model
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
    protected $table = 'teamManagementAction';

    /**
     * @var array
     */
    protected $fillable = ['title'];
}
