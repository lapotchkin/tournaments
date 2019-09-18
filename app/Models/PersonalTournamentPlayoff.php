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
 * App\Models\PersonalTournamentPlayoff
 *
 * @property int                                   $id            ID
 * @property int                                   $tournament_id ID турнира
 * @property int                                   $round         Круг
 * @property int                                   $pair          Пара
 * @property int|null                              $player_one_id ID первого игрока
 * @property int|null                              $player_two_id ID второго игрока
 * @property Carbon                                $createdAt     Дата создания
 * @property Carbon|null                           $deletedAt     Дата удаления
 * @property-read PersonalTournament|null          $tournament
 * @property-read Collection|PersonalGamePlayoff[] $games
 * @property-read Player|null                      $playerOne
 * @property-read Player|null                      $playerTwo
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|PersonalTournamentPlayoff newModelQuery()
 * @method static EloquentBuilder|PersonalTournamentPlayoff newQuery()
 * @method static QueryBuilder|PersonalTournamentPlayoff onlyTrashed()
 * @method static EloquentBuilder|PersonalTournamentPlayoff query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|PersonalTournamentPlayoff whereCreatedAt($value)
 * @method static EloquentBuilder|PersonalTournamentPlayoff whereDeletedAt($value)
 * @method static EloquentBuilder|PersonalTournamentPlayoff whereId($value)
 * @method static EloquentBuilder|PersonalTournamentPlayoff wherePair($value)
 * @method static EloquentBuilder|PersonalTournamentPlayoff wherePlayerOneId($value)
 * @method static EloquentBuilder|PersonalTournamentPlayoff wherePlayerTwoId($value)
 * @method static EloquentBuilder|PersonalTournamentPlayoff whereRound($value)
 * @method static EloquentBuilder|PersonalTournamentPlayoff whereTournamentId($value)
 * @method static QueryBuilder|PersonalTournamentPlayoff withTrashed()
 * @method static QueryBuilder|PersonalTournamentPlayoff withoutTrashed()
 * @mixin Eloquent
 */
class PersonalTournamentPlayoff extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;
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
    public function tournament()
    {
        return $this->belongsTo('App\Models\PersonalTournament', 'tournament_id');
    }

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
    public function games()
    {
        return $this->hasMany('App\Models\PersonalGamePlayoff', 'playoff_pair_id');
    }

    /**
     * @return int
     */
    public function getWinner()
    {
        if (!$this->playerOne || !$this->playerTwo) {
            return null;
        }
        $seriesResult = $this->getSeriesResult();
        if ($seriesResult[$this->playerOne->id] === $seriesResult[$this->playerTwo->id]) {
            return null;
        }
        return $seriesResult[$this->playerOne->id] > $seriesResult[$this->playerTwo->id]
            ? $this->playerOne->id
            : $this->playerTwo->id;
    }

    /**
     * @return array
     */
    public function getSeriesResult()
    {
        if (!$this->playerOne || !$this->playerTwo) {
            return null;
        }

        $series = [];
        $series[$this->playerOne->id] = 0;
        $series[$this->playerTwo->id] = 0;

        foreach ($this->games as $game) {
            if ($game->home_score > $game->away_score) {
                $series[$game->home_player_id] += 1;
            } else {
                $series[$game->away_player_id] += 1;
            }
        }

        return $series;
    }
}
