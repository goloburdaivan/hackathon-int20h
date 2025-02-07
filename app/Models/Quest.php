<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quest extends Model
{
    use CrudTrait;
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
