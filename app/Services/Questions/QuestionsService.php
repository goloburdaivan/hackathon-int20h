<?php

namespace App\Services\Questions;

use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Models\Quest;
use App\Models\Question;
use App\Repository\QuestionRepository;

class QuestionsService
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function create(Quest $quest, CreateQuestionRequest $request): Question
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('cover')->store('quests', 'public');
        }

        /** @var Question */
        return $this->questionRepository->create($data + [
                'quest_id' => $quest->id,
            ]);
    }
}
