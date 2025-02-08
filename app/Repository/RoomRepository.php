<?php

namespace App\Repository;

use App\Models\Room;

class RoomRepository extends AbstractRepository
{
    public function model(): string
    {
        return Room::class;
    }
}
