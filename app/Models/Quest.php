<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
