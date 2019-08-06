<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Club
 * @package App\Models
 * @property string                     $id
 * @property string                     $league_id
 * @property string                     $title
 * @property string                     $createdAt
 * @property string                     $deletedAt
 * @property League                     $league
 * @property PersonalTournamentPlayer[] $personalTournamentPlayers
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
    public function personalTournamentPlayers()
    {
        return $this->hasMany('App\Models\PersonalTournamentPlayer');
    }
}
