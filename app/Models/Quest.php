<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 */
class Quest extends Model
{
    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'cover',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quest_questions');
    }
}
