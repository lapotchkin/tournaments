<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PersonalGamePlayoff
 * @package App\Models
 * @property int                       $id
 * @property int                       $playoff_pair_id
 * @property int                       $home_player_id
 * @property int                       $away_player_id
 * @property boolean                   $home_score
 * @property boolean                   $away_score
 * @property boolean                   $isTechnicalDefeat
 * @property string                    $playedAt
 * @property string                    $createdAt
 * @property string                    $deletedAt
 * @property PersonalTournamentPlayoff $personalTournamentPlayoff
 * @property Player                    $homePlayer
 * @property Player                    $awayPlayer
 */
class PersonalGamePlayoff extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personalGamePlayoff';

    /**
     * @var array
     */
    protected $fillable = [
        'playoff_pair_id',
        'home_player_id',
        'away_player_id',
        'home_score',
        'away_score',
        'isTechnicalDefeat',
        'playedAt',
        'createdAt',
        'deletedAt',
    ];

    /**
     * @return BelongsTo
     */
    public function personalTournamentPlayoff()
    {
        return $this->belongsTo('App\Models\PersonalTournamentPlayoff', 'playoff_pair_id');
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
