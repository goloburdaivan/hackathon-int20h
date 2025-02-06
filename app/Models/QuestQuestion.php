<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QuestQuestion extends Model
{
    protected $fillable = [
        'quest_id',
        'question_id',
    ];
}
