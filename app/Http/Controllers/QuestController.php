<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quest\CreateQuestRequest;
use App\Http\Requests\Quest\UpdateQuestRequest;
use App\Http\Resources\Quest\QuestBaseResource;
use App\Models\Quest;
use App\Models\User;
use App\Services\Quest\QuestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class QuestController extends Controller
{
    public function __construct(
        private readonly QuestService $questService,
    ) {
    }

    public function index(): Response
    {
        return Inertia::render('Quest/Index', [
            'quests' => QuestBaseResource::collection($this->questService->getPaginated())->response()->getData(true),
        ]);
    }

    public function getUserQuests(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            'quests' => QuestBaseResource::collection($this->questService->getUserPaginated($user)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Quest/Create');
    }

    public function edit(): Response
    {
        return Inertia::render('Quest/Edit');
    }

    public function show(Quest $quest): Response
    {
        $quest = $this->questService->getQuestWithNumberOfQuestions($quest);

        return Inertia::render('Quest/Show', [
            'quest' => new QuestBaseResource($quest),
        ]);
    }

    /**
     * @throws \Exception
     */
    public function store(CreateQuestRequest $request): RedirectResponse
    {
        $quest = $this->questService->create($request);

        return redirect()->route('quest.edit', $quest);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateQuestRequest $request, Quest $quest): RedirectResponse
    {
        $this->questService->update($request, $quest);

        return redirect()->route('quest.edit', $quest);
    }

    public function delete(Quest $quest): RedirectResponse
    {
        $this->questService->delete($quest);

        return redirect()->route('quest.index');
    }
}
