<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\PersonalGameRegular
 *
 * @property int                     $id                ID
 * @property int                     $tournament_id     ID турнира
 * @property int|null                $round             Круг
 * @property int                     $home_player_id    ID хозяина
 * @property int                     $away_player_id    ID гостя
 * @property int|null                $home_score        Забил хозяин
 * @property int|null                $away_score        Забил гость
 * @property int                     $isOvertime        Овертайм
 * @property int                     $isShootout        Буллиты
 * @property int                     $isTechnicalDefeat Техническое поражение
 * @property string|null             $playedAt          Дата игры
 * @property Carbon                  $createdAt         Дата создания
 * @property string|null             $updatedAt         Дата изменения
 * @property Carbon|null             $deletedAt         Дата удаления
 * @property-read Player             $awayPlayer
 * @property-read Player             $homePlayer
 * @property-read PersonalTournament $tournament
 * @method static bool|null forceDelete()
 * @method static EloquentBuilder|PersonalGameRegular newModelQuery()
 * @method static EloquentBuilder|PersonalGameRegular newQuery()
 * @method static QueryBuilder|PersonalGameRegular onlyTrashed()
 * @method static EloquentBuilder|PersonalGameRegular query()
 * @method static bool|null restore()
 * @method static EloquentBuilder|PersonalGameRegular whereAwayPlayerId($value)
 * @method static EloquentBuilder|PersonalGameRegular whereAwayScore($value)
 * @method static EloquentBuilder|PersonalGameRegular whereCreatedAt($value)
 * @method static EloquentBuilder|PersonalGameRegular whereDeletedAt($value)
 * @method static EloquentBuilder|PersonalGameRegular whereHomePlayerId($value)
 * @method static EloquentBuilder|PersonalGameRegular whereHomeScore($value)
 * @method static EloquentBuilder|PersonalGameRegular whereId($value)
 * @method static EloquentBuilder|PersonalGameRegular whereIsOvertime($value)
 * @method static EloquentBuilder|PersonalGameRegular whereIsShootout($value)
 * @method static EloquentBuilder|PersonalGameRegular whereIsTechnicalDefeat($value)
 * @method static EloquentBuilder|PersonalGameRegular wherePlayedAt($value)
 * @method static EloquentBuilder|PersonalGameRegular whereRound($value)
 * @method static EloquentBuilder|PersonalGameRegular whereTournamentId($value)
 * @method static EloquentBuilder|PersonalGameRegular whereUpdatedAt($value)
 * @method static QueryBuilder|PersonalGameRegular withTrashed()
 * @method static QueryBuilder|PersonalGameRegular withoutTrashed()
 * @mixin Eloquent
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
    public function tournament()
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
