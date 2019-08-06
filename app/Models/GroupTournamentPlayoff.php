<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GroupTournamentPlayoff
 * @package App\Models
 * @property int                $id
 * @property int                $tournament_id
 * @property int                $team_one_id
 * @property int                $team_two_id
 * @property boolean            $round
 * @property boolean            $pair
 * @property string             $createdAt
 * @property string             $deletedAt
 * @property GroupTournament    $groupTournament
 * @property Team               $teamOne
 * @property Team               $teamTwo
 * @property GroupGamePlayoff[] $groupGamePlayoffs
 */
class GroupTournamentPlayoff extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupTournamentPlayoff';

    /**
     * @var array
     */
    protected $fillable = ['tournament_id', 'team_one_id', 'team_two_id', 'round', 'pair', 'createdAt', 'deletedAt'];

    /**
     * @return BelongsTo
     */
    public function groupTournament()
    {
        return $this->belongsTo('App\Models\GroupTournament', 'tournament_id');
    }

    /**
     * @return BelongsTo
     */
    public function teamOne()
    {
        return $this->belongsTo('App\Models\Team', 'team_one_id');
    }

    /**
     * @return BelongsTo
     */
    public function teamTwo()
    {
        return $this->belongsTo('App\Models\Team', 'team_two_id');
    }

    /**
     * @return HasMany
     */
    public function groupGamePlayoffs()
    {
        return $this->hasMany('App\Models\GroupGamePlayoff', 'playoff_pair_id');
    }
}
