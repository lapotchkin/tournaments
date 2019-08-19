<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AppTeam
 *
 * @property string    $app_id      ID игры
 * @property int       $team_id     ID команды
 * @property int       $app_team_id ID клуба в EASHL
 * @property-read App  $app
 * @property-read Team $team
 * @method static EloquentBuilder|AppTeam newModelQuery()
 * @method static EloquentBuilder|AppTeam newQuery()
 * @method static EloquentBuilder|AppTeam query()
 * @method static EloquentBuilder|AppTeam whereAppId($value)
 * @method static EloquentBuilder|AppTeam whereAppTeamId($value)
 * @method static EloquentBuilder|AppTeam whereTeamId($value)
 * @mixin Eloquent
 */
class AppTeam extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app_team';

    /**
     * @var array
     */
    protected $fillable = [];

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
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
