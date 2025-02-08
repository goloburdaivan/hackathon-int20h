<?php

namespace App\Listeners;

use App\Events\Room\GameCompletedEvent;
use App\Models\Room;
use App\Models\UserHistory;
use App\Repository\RoomRepository;
use Illuminate\Contracts\Queue\ShouldQueue;

class GameCompletedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly RoomRepository $roomRepository,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(GameCompletedEvent $event): void
    {
        /** @var Room $room */
        $room = $this->roomRepository->find($event->roomId);

        $participants = $room->participants()->orderByDesc('quest_id')->pluck('user_id')->toArray();

        $historyData = collect($participants)->map(fn($userId, $index) => [
            'user_id' => $userId,
            'quest_id' => $room->quest_id,
            'place' => $index + 1,
        ])->toArray();

        UserHistory::query()->insert($historyData);
    }
}
