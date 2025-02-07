<?php

namespace App\Http\Controllers;

use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Requests\Questions\UpdateQuestionRequest;
use App\Http\Resources\Question\QuestionResource;
use App\Models\Quest;
use App\Models\Question;
use App\Models\User;
use App\Services\Questions\QuestionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    public function __construct(
        private readonly QuestionsService $questionsService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function store(CreateQuestionRequest $request, Quest $quest): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($quest->owner_id !== $user->id) {
            return back()->withErrors([
                'error' => 'You can\'t create questions for other user\'s quest.'
            ]);
        }

        $this->questionsService->create($quest, $request);

        return redirect()->route('quest.edit', $quest);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateQuestionRequest $request, Quest $quest, Question $question): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($quest->owner_id !== $user->id) {
            return back()->withErrors([
                'error' => 'You can\'t edit other user\'s questions.'
            ]);
        }

        $this->questionsService->update($question, $request);

        return redirect()->route('quest.edit', $quest);
    }

    public function show(Quest $quest): JsonResponse
    {
        return response()->json([
            'question' => new QuestionResource($this->questionsService->findByQuest($quest->id)),
        ]);
    }

    public function destroy(Quest $quest, Question $question): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($quest->owner_id !== $user->id) {
            return back()->withErrors([
                'error' => 'You can\'t delete other user\'s question.'
            ]);
        }

        return redirect()->route('quest.edit', $quest);
    }
}
