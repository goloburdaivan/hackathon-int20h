<?php

namespace App\Listeners;

use App\Events\Room\UserJoinedRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserJoinedRoomListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserJoinedRoomEvent $event): void
    {
        $room = $event->room;
    }
}
