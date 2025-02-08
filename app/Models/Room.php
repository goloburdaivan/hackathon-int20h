<?php

namespace App\Models;

use App\Enums\RoomStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'owner_id',
        'quest_id',
        'max_participants',
        'status',
        'duration',
    ];

    protected $casts = [
        'status' => RoomStatusEnum::class,
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

    public function participants(): HasMany
    {
        return $this->hasMany(RoomParticipant::class);
    }
}
