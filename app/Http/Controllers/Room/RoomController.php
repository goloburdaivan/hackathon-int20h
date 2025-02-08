<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Http\Resources\Room\RoomResource;
use App\Models\Room;
use App\Models\User;
use App\Services\Room\RoomService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    public function index(Room $room): Response|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $room = $this->roomService->loadDetails($room);

        if (!$this->roomService->joinRoom($user, $room)) {
            return redirect()->route('quest.index');
        }

        return Inertia::render('Room/Index', [
            'room' => new RoomResource($room),
        ]);
    }
}
