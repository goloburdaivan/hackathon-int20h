<?php

namespace App\Services\Questions;

use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Requests\Questions\UpdateQuestionRequest;
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

    /**
     * @throws \Exception
     */
    public function update(Question $question, UpdateQuestionRequest $request): Question
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('cover')->store('quests', 'public');
        }

        /** @var Question */
        return $this->questionRepository->update($question, $data);
    }

    public function findByQuest(int $questId): Question
    {
        return $this->questionRepository->query()->where('quest_id', $questId)->firstOrFail();
    }

    public function delete(Question $question): void
    {
        $this->questionRepository->delete($question->id);
    }

    public function findById(int $id): ?Question
    {
        /** @var Question */
        return $this->questionRepository->find($id);
    }
}
