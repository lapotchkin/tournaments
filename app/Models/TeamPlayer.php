<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TeamPlayer
 * @package App\Models
 * @property int     $team_id
 * @property int     $player_id
 * @property boolean $isCaptain
 * @property string  $createdAt
 * @property string  $deletedAt
 * @property Player  $player
 * @property Team    $team
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
