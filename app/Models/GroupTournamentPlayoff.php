<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupTournamentPlayoff
 *
 * @property int                                $id            ID
 * @property int                                $tournament_id ID турнира
 * @property int                                $round         Круг
 * @property int                                $pair          Пара
 * @property int|null                           $team_one_id   ID первой команды
 * @property int|null                           $team_two_id   ID второй команды
 * @property Carbon                             $createdAt     Дата создания
 * @property-read Collection|GroupGamePlayoff[] $games
 * @property-read GroupTournament               $tournament
 * @property-read Team|null                     $teamOne
 * @property-read Team|null                     $teamTwo
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|GroupTournamentPlayoff newModelQuery()
 * @method static EloquentBuilder|GroupTournamentPlayoff newQuery()
 * @method static QueryBuilder|GroupTournamentPlayoff onlyTrashed()
 * @method static EloquentBuilder|GroupTournamentPlayoff query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|GroupTournamentPlayoff whereCreatedAt($value)
 * @method static EloquentBuilder|GroupTournamentPlayoff whereDeletedAt($value)
 * @method static EloquentBuilder|GroupTournamentPlayoff whereId($value)
 * @method static EloquentBuilder|GroupTournamentPlayoff wherePair($value)
 * @method static EloquentBuilder|GroupTournamentPlayoff whereRound($value)
 * @method static EloquentBuilder|GroupTournamentPlayoff whereTeamOneId($value)
 * @method static EloquentBuilder|GroupTournamentPlayoff whereTeamTwoId($value)
 * @method static EloquentBuilder|GroupTournamentPlayoff whereTournamentId($value)
 * @method static QueryBuilder|GroupTournamentPlayoff withTrashed()
 * @method static QueryBuilder|GroupTournamentPlayoff withoutTrashed()
 * @mixin Eloquent
 */
class GroupTournamentPlayoff extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groupTournamentPlayoff';

    /**
     * @var array
     */
    protected $fillable = ['tournament_id', 'team_one_id', 'team_two_id', 'round', 'pair', 'createdAt'];

    /**
     * @return BelongsTo
     */
    public function tournament()
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
    public function games()
    {
        return $this->hasMany('App\Models\GroupGamePlayoff', 'playoff_pair_id');
    }

    /**
     * @return int
     */
    public function getWinner()
    {
        if (!$this->teamOne || !$this->teamTwo) {
            return null;
        }
        $seriesResult = $this->getSeriesResult();
        if ($seriesResult[$this->teamOne->id] === $seriesResult[$this->teamTwo->id]) {
            return null;
        }
        return $seriesResult[$this->teamOne->id] > $seriesResult[$this->teamTwo->id]
            ? $this->teamOne->id
            : $this->teamTwo->id;
    }

    /**
     * @return array
     */
    public function getSeriesResult()
    {
        if (!$this->teamOne || !$this->teamTwo) {
            return null;
        }

        $series = [];
        $series[$this->teamOne->id] = 0;
        $series[$this->teamTwo->id] = 0;

        foreach ($this->games as $game) {
            if ($game->home_score > $game->away_score) {
                $series[$game->home_team_id] += 1;
            } else {
                $series[$game->away_team_id] += 1;
            }
        }

        return $series;
    }

    public function getSafePlayersData()
    {
        $players = [
            'home' => [],
            'away' => [],
        ];
        foreach ($this->teamOne->players as $player) {
            $players['home'][] = $player->getSafeData();
        }
        foreach ($this->teamTwo->players as $player) {
            $players['away'][] = $player->getSafeData();
        }
        return $players;
    }
}
