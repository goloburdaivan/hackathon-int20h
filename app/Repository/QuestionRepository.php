<?php

namespace App\Repository;

use App\Models\Question;

class QuestionRepository extends AbstractRepository
{
    public function model(): string
    {
        return Question::class;
    }
}
