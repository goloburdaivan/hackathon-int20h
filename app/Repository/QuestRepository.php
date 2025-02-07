<?php

namespace App\Repository;

use App\Models\Quest;
use Illuminate\Database\Eloquent\Collection;

class QuestRepository extends AbstractRepository
{
    public function model(): string
    {
        return Quest::class;
    }

    public function findByOwner(int $id): Collection
    {
        return $this->query()->where('owner_id', $id)->get();
    }
}
