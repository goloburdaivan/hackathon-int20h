<?php

namespace App\Events\Room;

use App\Models\Room;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserMovedToQuestionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $roomId,
        public User $user,
        public int $questionId,
    )
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel("room-{$this->roomId}");
    }

    public function broadcastAs()
    {
        return "UserMovedToQuestionEvent";
    }

    public function broadcastWith()
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'question_id' => $this->questionId,
        ];
    }
}
