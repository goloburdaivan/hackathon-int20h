<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property int $quest_id
 * @property int $place
 */
class UserHistory extends Model
{
    protected $fillable = [
        'user_id',
        'quest_id',
        'place',
    ];

    protected $table = 'user_games_history';
}
