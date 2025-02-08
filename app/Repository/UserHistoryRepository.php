<?php

namespace App\Repository;

use App\Models\UserHistory;

class UserHistoryRepository extends AbstractRepository
{
    public function model(): string
    {
        return UserHistory::class;
    }
}
