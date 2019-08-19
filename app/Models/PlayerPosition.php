<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PlayerPosition
 *
 * @property int                                      $id
 * @property string                                   $title       Название
 * @property string                                   $short_title Краткое название
 * @property string                                   $createdAt
 * @property string|null                              $deletedAt
 * @property-read Collection|GroupGamePlayoffPlayer[] $groupGamePlayoffPlayers
 * @property-read Collection|GroupGameRegularPlayer[] $groupGameRegularPlayers
 * @method static EloquentBuilder|PlayerPosition newModelQuery()
 * @method static EloquentBuilder|PlayerPosition newQuery()
 * @method static EloquentBuilder|PlayerPosition query()
 * @method static EloquentBuilder|PlayerPosition whereCreatedAt($value)
 * @method static EloquentBuilder|PlayerPosition whereDeletedAt($value)
 * @method static EloquentBuilder|PlayerPosition whereId($value)
 * @method static EloquentBuilder|PlayerPosition whereShortTitle($value)
 * @method static EloquentBuilder|PlayerPosition whereTitle($value)
 * @mixin \Eloquent
 */
class PlayerPosition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player_position';

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
        return $this->hasMany('App\Models\GroupGamePlayoffPlayer', 'position_id');
    }

    /**
     * @return HasMany
     */
    public function groupGameRegularPlayers()
    {
        return $this->hasMany('App\Models\GroupGameRegularPlayer', 'position_id');
    }
}
