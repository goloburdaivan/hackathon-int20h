<?php

namespace App\Services;

use App\Http\Requests\UpdateProfileRequest;
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

        $path = $request->file('avatar')->store('avatars', 'public');

        /** @var User */
        return $this->userRepository->update($user, ['avatar' => $path]);
    }
}
