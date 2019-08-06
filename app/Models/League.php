<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class League
 * @package App\Models
 * @property string               $id
 * @property string               $title
 * @property string               $createdAt
 * @property string               $deletedAt
 * @property Club[]               $clubs
 * @property PersonalTournament[] $personalTournaments
 */
class League extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'createdAt';
    const DELETED_AT = 'deletedAt';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'league';

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
    public function clubs()
    {
        return $this->hasMany('App\Models\Club');
    }

    /**
     * @return HasMany
     */
    public function personalTournaments()
    {
        return $this->hasMany('App\Models\PersonalTournament');
    }
}
