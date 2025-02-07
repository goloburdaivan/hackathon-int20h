<?php

namespace App\Services\User;

use App\Repository\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProfileService
{


    public function __construct(
        protected UserRepository $userRepository,
    )
    {
    }

    public function getProfile(int $userId): Model
    {
        return $this->userRepository->find($userId);
    }

    /**
     * @throws Exception
     */
    public function updateProfile(int $user, array $data): Model
    {
        $user = $this->userRepository->find($user);

        if (!$user)
        {
            throw new Exception("User not found");
        }

        $this->userRepository->update($user, $data);

        if (isset($data['avatar']) && $data['avatar'] instanceof Request) {
            $data['avatar'] = $this->uploadAvatar($data['avatar']);
        }

        return $this->userRepository->create($data);
    }

    public function deleteProfile(int $userId): bool
    {
        return $this->userRepository->delete($userId);
    }

    private function uploadAvatar(Request $request): string
    {
        return $request->file('avatar')->store('avatars');
    }
}
