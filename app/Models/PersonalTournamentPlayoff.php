<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PersonalTournamentPlayoff
 * @package App\Models
 * @property int                   $id
 * @property int                   $player_one_id
 * @property int                   $player_two_id
 * @property int                   $tournament_id
 * @property boolean               $round
 * @property boolean               $pair
 * @property string                $createdAt
 * @property string                $deletedAt
 * @property Player                $playerOne
 * @property Player                $playerTwo
 * @property PersonalGamePlayoff[] $personalGamePlayoffs
 */
class PersonalTournamentPlayoff extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personalTournamentPlayoff';

    /**
     * @var array
     */
    protected $fillable = [
        'player_one_id',
        'player_two_id',
        'tournament_id',
        'round',
        'pair',
        'createdAt',
        'deletedAt',
    ];

    /**
     * @return BelongsTo
     */
    public function playerOne()
    {
        return $this->belongsTo('App\Models\Player', 'player_one_id');
    }

    /**
     * @return BelongsTo
     */
    public function playerTwo()
    {
        return $this->belongsTo('App\Models\Player', 'player_two_id');
    }

    /**
     * @return HasMany
     */
    public function personalGamePlayoffs()
    {
        return $this->hasMany('App\Models\PersonalGamePlayoff', 'playoff_pair_id');
    }
}
