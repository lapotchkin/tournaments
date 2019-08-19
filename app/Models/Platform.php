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
 * App\Models\Platform
 *
 * @property string                               $id        ID
 * @property string                               $name      Название
 * @property string|null                          $icon      Иконка
 * @property Carbon                               $createdAt Дата создания
 * @property Carbon|null                          $deletedAt Дата удаления
 * @property-read Collection|GroupTournament[]    $groupTournaments
 * @property-read Collection|PersonalTournament[] $personalTournaments
 * @property-read Collection|Player[]             $players
 * @property-read Collection|Team[]               $teams
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|Platform newModelQuery()
 * @method static EloquentBuilder|Platform newQuery()
 * @method static QueryBuilder|Platform onlyTrashed()
 * @method static EloquentBuilder|Platform query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|Platform whereCreatedAt($value)
 * @method static EloquentBuilder|Platform whereDeletedAt($value)
 * @method static EloquentBuilder|Platform whereIcon($value)
 * @method static EloquentBuilder|Platform whereId($value)
 * @method static EloquentBuilder|Platform whereName($value)
 * @method static QueryBuilder|Platform withTrashed()
 * @method static QueryBuilder|Platform withoutTrashed()
 * @mixin Eloquent
 */
class Platform extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'platform';

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
    protected $fillable = ['name', 'icon', 'createdAt', 'deletedAt'];

    /**
     * @return HasMany
     */
    public function groupTournaments()
    {
        return $this->hasMany('App\Models\GroupTournament')
            ->orderByDesc('createdAt');
    }

    /**
     * @return HasMany
     */
    public function personalTournaments()
    {
        return $this->hasMany('App\Models\PersonalTournament')
            ->orderByDesc('createdAt');
    }

    /**
     * @return HasMany
     */
    public function players()
    {
        return $this->hasMany('App\Models\Player');
    }

    /**
     * @return HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }
}
