<?php

namespace App\Events\Room;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedRoomEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Room $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("room-{$this->room->id}"),
        ];
    }
}
