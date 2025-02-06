<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quest extends Model
{
    protected $fillable = [
        'name',
        'description',
        'owner_id',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quest_questions');
    }
}
