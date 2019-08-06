<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PersonalTournament
 * @package App\Models
 * @property int                        $id
 * @property string                     $platform_id
 * @property string                     $app_id
 * @property string                     $league_id
 * @property string                     $title
 * @property boolean                    $playoff_rounds
 * @property string                     $createdAt
 * @property string                     $deletedAt
 * @property App                        $app
 * @property League                     $league
 * @property Platform                   $platform
 * @property PersonalGameRegular[]      $personalGameRegulars
 * @property PersonalTournamentPlayer[] $personalTournamentPlayers
 */
class PersonalTournament extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personalTournament';

    /**
     * @var array
     */
    protected $fillable = ['platform_id', 'app_id', 'league_id', 'title', 'playoff_rounds', 'createdAt', 'deletedAt'];

    /**
     * @return BelongsTo
     */
    public function app()
    {
        return $this->belongsTo('App\Models\App');
    }

    /**
     * @return BelongsTo
     */
    public function league()
    {
        return $this->belongsTo('App\Models\League');
    }

    /**
     * @return BelongsTo
     */
    public function platform()
    {
        return $this->belongsTo('App\Models\Platform');
    }

    /**
     * @return HasMany
     */
    public function personalGameRegulars()
    {
        return $this->hasMany('App\Models\PersonalGameRegular', 'tournament_id');
    }

    /**
     * @return HasMany
     */
    public function personalTournamentPlayers()
    {
        return $this->hasMany('App\Models\PersonalTournamentPlayer', 'tournament_id');
    }
}
