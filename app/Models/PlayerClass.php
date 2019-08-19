<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PlayerClass
 *
 * @property int                                      $id
 * @property string                                   $title       Название
 * @property string                                   $short_title Краткое название
 * @property string                                   $createdAt
 * @property string|null                              $deletedAt
 * @property-read Collection|GroupGamePlayoffPlayer[] $groupGamePlayoffPlayers
 * @property-read Collection|GroupGameRegularPlayer[] $groupGameRegularPlayers
 * @method static EloquentBuilder|PlayerClass newModelQuery()
 * @method static EloquentBuilder|PlayerClass newQuery()
 * @method static EloquentBuilder|PlayerClass query()
 * @method static EloquentBuilder|PlayerClass whereCreatedAt($value)
 * @method static EloquentBuilder|PlayerClass whereDeletedAt($value)
 * @method static EloquentBuilder|PlayerClass whereId($value)
 * @method static EloquentBuilder|PlayerClass whereShortTitle($value)
 * @method static EloquentBuilder|PlayerClass whereTitle($value)
 * @mixin Eloquent
 */
class PlayerClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player_class';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'boolean';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['title', 'short_title', 'createdAt', 'deletedAt'];

    /**
     * @return HasMany
     */
    public function groupGamePlayoffPlayers()
    {
        return $this->hasMany('App\Models\GroupGamePlayoffPlayer', 'class_id');
    }

    /**
     * @return HasMany
     */
    public function groupGameRegularPlayers()
    {
        return $this->hasMany('App\Models\GroupGameRegularPlayer', 'class_id');
    }
}
