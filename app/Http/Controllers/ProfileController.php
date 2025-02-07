<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\User\UserProfileResource;
use App\Models\User;
use App\Repository\QuestRepository;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(
        private readonly QuestRepository $questRepository,
        private readonly UserService $userService,
    ) {
    }

    public function index(): Response
    {
        /** @var User $user */
        $user = auth()->user();
        $quests = $this->questRepository->findByOwner($user->id);

        return Inertia::render('Profile/Profile', [
            'user' => new UserProfileResource($user),
            'quests' => $quests,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $this->userService->updateProfile($user, $request);

        return back()->with('success', 'Профіль оновлено!');
    }
}
