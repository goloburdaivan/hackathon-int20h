<?php

namespace App\Http\Controllers;

use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Models\Quest;
use App\Models\User;
use App\Services\Questions\QuestionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(
        private readonly QuestionsService $questionsService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function store(Quest $quest, CreateQuestionRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($quest->owner_id !== $user->id) {
            return back()->withErrors([
                'error' => 'You can\'t edit other user\'s questions.'
            ]);
        }

        $this->questionsService->create($quest, $request);

        return redirect()->route('quest.edit', $quest);
    }

    public function show(Request $request): JsonResponse
    {
        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        return response()->json();
    }

    public function destroy(Request $request): JsonResponse
    {
        return response()->json();
    }
}
