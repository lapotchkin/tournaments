<?php

namespace App\Models;

use Eloquent;
use Jenssegers\Mongodb\Eloquent\Model as EloquentMongo;

/**
 * Class EaGame
 *
 * @property string $matchId
 * @property int    $timestamp
 * @mixin Eloquent
 * @package App\Models
 */
class EaGame extends EloquentMongo
{
    protected $connection = 'mongodb';
    protected $collection = 'games';
    protected $fillable = [
        'matchId',
        'timestamp',
        'timeAgo',
        'clubs',
        'players',
        'aggregate',
        'isImported',
    ];
}
