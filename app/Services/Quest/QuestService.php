<?php

namespace App\Services\Quest;

use App\Filters\Quest\NumberOfQuestionsQueryFilter;
use App\Filters\Quest\SearchQuestQueryFilter;
use App\Http\Requests\Quest\CreateQuestRequest;
use App\Http\Requests\Quest\UpdateQuestRequest;
use App\Models\Quest;
use App\Models\User;
use App\Repository\QuestRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

class QuestService
{
    public function __construct(
        private readonly QuestRepository $questRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function create(CreateQuestRequest $request): Quest
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('quests', 'public');
        }

        /** @var Quest */
        return $this->questRepository->create($data + [
            'owner_id' => $request->user()->id,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateQuestRequest $request, Quest $quest): Quest
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('quests', 'public');
        }

        /** @var Quest */
        return $this->questRepository->update($quest, $data);
    }

    public function delete(Quest $quest): bool
    {
        return $this->questRepository->delete($quest->id);
    }

    public function getQuestWithNumberOfQuestions(Quest $quest): ?Quest
    {
        /** @var Quest */
        return $quest
            ->loadCount('questions');
    }

    public function getPaginated(int $pages = 10): LengthAwarePaginator
    {
        $query = $this->questRepository
            ->query()
            ->withCount('questions');

        $questsQuery = app(Pipeline::class)
            ->send($query)
            ->through([
                SearchQuestQueryFilter::class,
                NumberOfQuestionsQueryFilter::class,
            ])
            ->thenReturn();

        return $questsQuery->paginate($pages);
    }

    public function getUserPaginated(User $user): LengthAwarePaginator
    {
        $query = $this->questRepository
            ->query()
            ->where('owner_id', $user->id)
            ->withCount('questions');

        $questsQuery = app(Pipeline::class)
            ->send($query)
            ->through([
                SearchQuestQueryFilter::class,
                NumberOfQuestionsQueryFilter::class,
            ])
            ->thenReturn();

        return $questsQuery->paginate($pages);
    }
}
