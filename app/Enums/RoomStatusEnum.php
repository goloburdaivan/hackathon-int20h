<?php

namespace App\Enums;

enum RoomStatusEnum: string
{
    case WAITING = 'WAITING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
}
