<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\PersonalGamePlayoff
 *
 * @property int                            $id                ID
 * @property int                            $playoff_pair_id   ID пары плейоф
 * @property int                            $home_player_id    ID хозяина
 * @property int                            $away_player_id    ID гостя
 * @property int|null                       $home_score        Забил хозяин
 * @property int|null                       $away_score        Забил гость
 * @property int                            $isTechnicalDefeat Техническое поражение
 * @property string|null                    $playedAt          Дата игры
 * @property Carbon                         $createdAt         Дата создания
 * @property Carbon|null                    $deletedAt         Дата удаления
 * @property-read Player                    $awayPlayer
 * @property-read Player                    $homePlayer
 * @property-read PersonalTournamentPlayoff $playoffPair
 * @property-read PersonalTournament        $tournament
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|PersonalGamePlayoff newModelQuery()
 * @method static EloquentBuilder|PersonalGamePlayoff newQuery()
 * @method static QueryBuilder|PersonalGamePlayoff onlyTrashed()
 * @method static EloquentBuilder|PersonalGamePlayoff query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|PersonalGamePlayoff whereAwayPlayerId($value)
 * @method static EloquentBuilder|PersonalGamePlayoff whereAwayScore($value)
 * @method static EloquentBuilder|PersonalGamePlayoff whereCreatedAt($value)
 * @method static EloquentBuilder|PersonalGamePlayoff whereDeletedAt($value)
 * @method static EloquentBuilder|PersonalGamePlayoff whereHomePlayerId($value)
 * @method static EloquentBuilder|PersonalGamePlayoff whereHomeScore($value)
 * @method static EloquentBuilder|PersonalGamePlayoff whereId($value)
 * @method static EloquentBuilder|PersonalGamePlayoff whereIsTechnicalDefeat($value)
 * @method static EloquentBuilder|PersonalGamePlayoff wherePlayedAt($value)
 * @method static EloquentBuilder|PersonalGamePlayoff wherePlayoffPairId($value)
 * @method static QueryBuilder|PersonalGamePlayoff withTrashed()
 * @method static QueryBuilder|PersonalGamePlayoff withoutTrashed()
 * @mixin Eloquent
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
    public function playoffPair()
    {
        return $this->belongsTo('App\Models\PersonalTournamentPlayoff', 'playoff_pair_id');
    }

    /**
     * @return HasOneThrough
     */
    public function tournament()
    {
        return $this->hasOneThrough(
            'App\Models\PersonalTournament',
            'App\Models\PersonalTournamentPlayoff',
            'id',
            'id',
            'playoff_pair_id',
            'tournament_id'
        );
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
