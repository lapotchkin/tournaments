<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GroupGameRegular
 * @package App\Models
 * @property int                      $id
 * @property int                      $tournament_id
 * @property int                      $home_team_id
 * @property int                      $away_team_id
 * @property int                      $round
 * @property boolean                  $home_score
 * @property boolean                  $away_score
 * @property boolean                  $home_shot
 * @property boolean                  $away_shot
 * @property boolean                  $home_hit
 * @property boolean                  $away_hit
 * @property string                   $home_attack_time
 * @property string                   $away_attack_time
 * @property float                    $home_pass_percent
 * @property float                    $away_pass_percent
 * @property boolean                  $home_faceoff
 * @property boolean                  $away_faceoff
 * @property string                   $home_penalty_time
 * @property string                   $away_penalty_time
 * @property boolean                  $home_penalty_total
 * @property boolean                  $away_penalty_total
 * @property boolean                  $home_penalty_success
 * @property boolean                  $away_penalty_success
 * @property string                   $home_powerplay_time
 * @property string                   $away_powerplay_time
 * @property boolean                  $home_shorthanded_goal
 * @property boolean                  $away_shorthanded_goal
 * @property boolean                  $isOvertime
 * @property boolean                  $isShootout
 * @property boolean                  $isTechnicalDefeat
 * @property string                   $createdAt
 * @property string                   $playedAt
 * @property string                   $deletedAt
 * @property GroupTournamentTeam      $homeTeam
 * @property GroupTournamentTeam      $awayTeam
 * @property GroupTournament          $tournament
 * @property GroupGameRegularPlayer[] $protocols
 */
class GroupGameRegular extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupGameRegular';

    /**
     * @var GroupGameRegularPlayer[]
     */
    public $homeProtocols = [];
    /**
     * @var GroupGameRegularPlayer[]
     */
    public $awayProtocols = [];
    /**
     * @var null|GroupGameRegularPlayer
     */
    public $homeGoalie = null;
    /**
     * @var null|GroupGameRegularPlayer
     */
    public $awayGoalie = null;

    /**
     * @var array
     */
    protected $fillable = [
        'tournament_id',
        'home_team_id',
        'away_team_id',
        'round',
        'home_score',
        'away_score',
        'home_shot',
        'away_shot',
        'home_hit',
        'away_hit',
        'home_attack_time',
        'away_attack_time',
        'home_pass_percent',
        'away_pass_percent',
        'home_faceoff',
        'away_faceoff',
        'home_penalty_time',
        'away_penalty_time',
        'home_penalty_total',
        'away_penalty_total',
        'home_penalty_success',
        'away_penalty_success',
        'home_powerplay_time',
        'away_powerplay_time',
        'home_shorthanded_goal',
        'away_shorthanded_goal',
        'isOvertime',
        'isShootout',
        'isTechnicalDefeat',
        'createdAt',
        'playedAt',
        'deletedAt',
    ];

    /**
     * @return BelongsTo
     */
    public function homeTeam()
    {
        return $this->belongsTo('App\Models\GroupTournamentTeam', 'home_team_id', 'team_id');
    }

    /**
     * @return BelongsTo
     */
    public function awayTeam()
    {
        return $this->belongsTo('App\Models\GroupTournamentTeam', 'away_team_id', 'team_id');
    }

    /**
     * @return BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\GroupTournament', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function protocols()
    {
        return $this->hasMany('App\Models\GroupGameRegularPlayer', 'game_id');
    }
}
