<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Platform
 * @package App\Models
 * @property string               $id
 * @property string               $name
 * @property string               $icon
 * @property string               $createdAt
 * @property string               $deletedAt
 * @property GroupTournament[]    $groupTournaments
 * @property PersonalTournament[] $personalTournaments
 * @property Player[]             $players
 * @property Team[]               $teams
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
