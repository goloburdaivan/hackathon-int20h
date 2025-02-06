<?php

namespace App\Services\User;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Repository\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function login(LoginRequest $request): bool
    {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return false;
        }

        session()->regenerate();

        return true;
    }

    /**
     * @throws \Exception
     */
    public function register(RegisterRequest $request): User
    {
        $data = $request->validated();

        /** @var User */
        return $this->userRepository->create($data);
    }

    public function logout(): void
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();
    }

    /**
     * @throws \Exception
     */
    public function handleOAuthLogin(): bool
    {
        $githubUser = Socialite::driver('github')->user();

        if (!$githubUser) {
            return false;
        }

        $user = $this->userRepository->findByEmail($githubUser->email);

        if ($user) {
            Auth::login($user);
            return true;
        }

        /** @var User $user */
        $user = $this->userRepository->create([
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'password' => Hash::make(Str::random(15)),
        ]);

        Auth::login($user);

        return true;
    }
}
