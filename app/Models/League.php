<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\League
 *
 * @property string                               $id        ID
 * @property string                               $title     Название
 * @property Carbon                               $createdAt Дата создания
 * @property Carbon|null                          $deletedAt Дата удаления
 * @property-read Collection|Club[]               $clubs
 * @property-read Collection|PersonalTournament[] $personalTournaments
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|League newModelQuery()
 * @method static EloquentBuilder|League newQuery()
 * @method static QueryBuilder|League onlyTrashed()
 * @method static EloquentBuilder|League query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|League whereCreatedAt($value)
 * @method static EloquentBuilder|League whereDeletedAt($value)
 * @method static EloquentBuilder|League whereId($value)
 * @method static EloquentBuilder|League whereTitle($value)
 * @method static QueryBuilder|League withTrashed()
 * @method static QueryBuilder|League withoutTrashed()
 * @mixin Eloquent
 */
class League extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'league';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['title', 'createdAt', 'deletedAt'];

    /**
     * @return HasMany
     */
    public function clubs()
    {
        return $this->hasMany('App\Models\Club');
    }

    /**
     * @return HasMany
     */
    public function personalTournaments()
    {
        return $this->hasMany('App\Models\PersonalTournament');
    }
}
