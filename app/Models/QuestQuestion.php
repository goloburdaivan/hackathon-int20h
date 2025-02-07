<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QuestQuestion extends Model
{
    use CrudTrait;
    protected $fillable = [
        'quest_id',
        'question_id',
    ];
}
