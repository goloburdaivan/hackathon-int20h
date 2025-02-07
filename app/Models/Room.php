<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use CrudTrait;
    protected $fillable = [
        'owner_id',
        'max_participants',
        'status',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'room_participants');
    }

    public function roomParticipants(): HasMany
    {
        return $this->hasMany(RoomParticipant::class);
    }
}
