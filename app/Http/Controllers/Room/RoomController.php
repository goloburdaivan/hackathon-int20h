<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Http\Resources\Room\RoomResource;
use App\Models\Room;
use App\Models\User;
use App\Services\Room\RoomService;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    public function index(Room $room): Response
    {
        /** @var User $user */
        $user = auth()->user();
        $room = $this->roomService->loadDetails($room);

        $this->roomService->joinRoom($user, $room);

        return Inertia::render('Room/Index', [
            'room' => new RoomResource($room),
        ]);
    }
}
