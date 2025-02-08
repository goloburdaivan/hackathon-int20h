<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\RollDiceRequest;
use App\Models\Room;
use App\Models\User;
use App\Services\Room\RoomService;
use Illuminate\Http\Response;

class DiceController extends Controller
{
    public function __construct(
        private readonly RoomService $roomService,
    ) {
    }

    public function rollDice(Room $room, RollDiceRequest $request): Response
    {
        /** @var User $user */
        $user = auth()->user();
        $this->roomService->move($room, $user, $request);

        return response()->noContent();
    }
}
