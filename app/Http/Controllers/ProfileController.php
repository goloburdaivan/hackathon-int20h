<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\User\ProfileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService,
    )
    {
    }

    public function show(): Response
    {
        $profile = $this->profileService->getProfile(Auth::id());

        return Inertia::render('Profile/Show', [
            'profile' => $profile
        ]);
    }

    /**
     * @throws Exception
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $userId = auth()->id();

        if ($request->hasFile('avatar')) {
            $validatedData['avatar'] = $request->file('avatar');
        }

        $profile = $this->profileService->updateProfile($userId, $validatedData);

        return response()->json($profile);
    }

    public function delete(): JsonResponse
    {
        $this->profileService->deleteProfile(Auth::id());

        return response()->json(null, 204);
    }
}
