<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PersonalGameRegular
 * @package App\Models
 * @property int                $id
 * @property int                $tournament_id
 * @property int                $home_player_id
 * @property int                $away_player_id
 * @property boolean            $round
 * @property boolean            $home_score
 * @property boolean            $away_score
 * @property boolean            $isOvertime
 * @property boolean            $isShootout
 * @property boolean            $isTechnicalDefeat
 * @property string             $playedAt
 * @property string             $createdAt
 * @property string             $deletedAt
 * @property PersonalTournament $personalTournament
 * @property Player             $homePlayer
 * @property Player             $awayPlayer
 */
class PersonalGameRegular extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personalGameRegular';

    /**
     * @var array
     */
    protected $fillable = [
        'tournament_id',
        'home_player_id',
        'away_player_id',
        'round',
        'home_score',
        'away_score',
        'isOvertime',
        'isShootout',
        'isTechnicalDefeat',
        'playedAt',
        'createdAt',
        'deletedAt',
    ];

    /**
     * @return BelongsTo
     */
    public function personalTournament()
    {
        return $this->belongsTo('App\Models\PersonalTournament', 'tournament_id');
    }

    /**
     * @return BelongsTo
     */
    public function homePlayer()
    {
        return $this->belongsTo('App\Models\Player', 'home_player_id');
    }

    /**
     * @return BelongsTo
     */
    public function awayPlayer()
    {
        return $this->belongsTo('App\Models\Player', 'away_player_id');
    }
}
