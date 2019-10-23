<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * Class EaGame
 * @property string $matchId
 * @property int $timestamp
 * @package App\Models
 */
class EaGame extends Eloquent
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
