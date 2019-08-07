<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class App
 * @package App\Models
 * @property string               $id
 * @property string               $title
 * @property string               $createdAt
 * @property string               $deletedAt
 * @property GroupTournament[]    $groupTournaments
 * @property PersonalTournament[] $personalTournaments
 */
class App extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['title', 'createdAt', 'deletedAt'];

    /**
     * @return HasMany
     */
    public function groupTournaments()
    {
        return $this->hasMany('App\Models\GroupTournament')
            ->orderByDesc('createdAt');
    }

    /**
     * @return HasMany
     */
    public function personalTournaments()
    {
        return $this->hasMany('App\Models\PersonalTournament')
            ->orderByDesc('createdAt');
    }
}
