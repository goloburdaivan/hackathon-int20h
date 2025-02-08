<?php

namespace App\Services\Room;

use App\Events\Room\UserJoinedRoomEvent;
use App\Events\Room\UserMovedToQuestionEvent;
use App\Http\Requests\Room\RollDiceRequest;
use App\Models\Room;
use App\Models\User;
use App\Repository\RoomRepository;

class RoomService
{
    public function __construct(
        private readonly RoomRepository $roomRepository,
    ) {
    }

    public function loadDetails(Room $room): Room
    {
        return $room
            ->load([
                'owner',
                'quest',
                'quest.questions',
                'participants.user',
            ]);
    }

    public function joinRoom(User $user, Room $room): void
    {
        if ($room->users()->where('user_id', $user->id)->exists()) {
            return;
        }

        $room->users()->attach($user, [
            'question_id' => 1,
        ]);

        event(new UserJoinedRoomEvent($room));
    }

    public function move(Room $room, User $user, RollDiceRequest $request): void
    {
        $data = $request->validated();

        $room->users()->updateExistingPivot($user->id, [
            'question_id' => $data['question_id'],
        ]);

        event(new UserMovedToQuestionEvent(
            $room->id,
            $user,
            $data['question_id']
        ));
    }
}
