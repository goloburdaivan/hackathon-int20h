<?php

namespace App\Services;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function updateProfile(User $user, UpdateProfileRequest $request): ?User
    {
        $data = $request->validated();
        $user = $this->userRepository->update($user, $data);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user = $this->userRepository->update($user, ['avatar' => $path]);
        }

        /** @var User */
        return $user;
    }
}
