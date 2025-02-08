<?php

namespace App\Services\Room;

use App\Enums\RoomStatusEnum;
use App\Events\Room\GameStartedEvent;
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

    /**
     * @throws \Exception
     */
    public function joinRoom(User $user, Room $room): bool
    {
        if ($room->users()->where('user_id', $user->id)->exists()) {
            return true;
        }

        if ($room->max_participants === $room->users()->count()) {
            return false;
        }

        $room->users()->attach($user, [
            'question_id' => 1,
        ]);

        if ($room->max_participants === $room->users()->count()) {
            $this->roomRepository->update($room, [
                'status' => RoomStatusEnum::IN_PROGRESS,
            ]);
            event(new GameStartedEvent($room->id));
        }

        event(new UserJoinedRoomEvent($room->id, $user));

        return true;
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
