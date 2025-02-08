<?php

namespace App\Http\Resources\Room;

use App\Http\Resources\Quest\QuestResource;
use App\Http\Resources\User\UserProfileResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Room */
class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner' => new UserProfileResource($this->whenLoaded('owner')),
            'quest' => new QuestResource($this->whenLoaded('quest')),
            'users' => $this->participants->map(function ($participant) {
                return [
                    'user' => new UserProfileResource($participant->user),
                    'current_question_id' => $participant->question_id,
                ];
            }),
            'max_participants' => $this->max_participants,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
