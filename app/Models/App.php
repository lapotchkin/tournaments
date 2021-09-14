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
 * App\Models\App
 *
 * @property string                               $id        ID
 * @property string                               $title     Игра
 * @property Carbon                               $createdAt Дата создания
 * @property Carbon|null                          $deletedAt Дата удаления
 * @property-read Collection|GroupTournament[]    $groupTournaments
 * @property-read Collection|PersonalTournament[] $personalTournaments
 * @property-read Collection|AppTeam[]            $appTeams
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|App newModelQuery()
 * @method static EloquentBuilder|App newQuery()
 * @method static QueryBuilder|App onlyTrashed()
 * @method static EloquentBuilder|App query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|App whereCreatedAt($value)
 * @method static EloquentBuilder|App whereDeletedAt($value)
 * @method static EloquentBuilder|App whereId($value)
 * @method static EloquentBuilder|App whereTitle($value)
 * @method static QueryBuilder|App withTrashed()
 * @method static QueryBuilder|App withoutTrashed()
 * @mixin Eloquent
 */
class App extends Model
{
    use SoftDeletes;

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;
    public const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app';

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
    public function groupTournaments()
    : HasMany
    {
        return $this->hasMany('App\Models\GroupTournament')
            ->orderByDesc('createdAt');
    }

    /**
     * @return HasMany
     */
    public function personalTournaments()
    : HasMany
    {
        return $this->hasMany('App\Models\PersonalTournament')
            ->orderByDesc('createdAt');
    }

    /**
     * @return HasMany
     */
    public function appTeams()
    : HasMany
    {
        return $this->hasMany('App\Models\AppTeam');
    }
}
