<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int              $id
 * @property int              $game_id
 * @property int              $team_id
 * @property int              $player_id
 * @property boolean          $goals
 * @property boolean          $assists
 * @property boolean          $isGoalie
 * @property string           $createdAt
 * @property string           $deletedAt
 * @property GroupGamePlayoff $groupGamePlayoff
 * @property Player           $player
 * @property Team             $team
 */
class GroupGamePlayoffPlayer extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupGamePlayoff_player';

    /**
     * @var array
     */
    protected $fillable = ['game_id', 'team_id', 'player_id', 'goals', 'assists', 'isGoalie', 'createdAt', 'deletedAt'];

    /**
     * @return BelongsTo
     */
    public function groupGamePlayoff()
    {
        return $this->belongsTo('App\Models\GroupGamePlayoff', 'game_id');
    }

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
