<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\User\AuthService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function loginIndex(): Response
    {
        return Inertia::render('Login/Index');
    }

    public function registerIndex(): Response
    {
        return Inertia::render('Register/Index');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (!$this->authService->login($request)) {
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ]);
        }

        return redirect()->route('home');
    }

    /**
     * @throws \Exception
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->register($request);

        return redirect()->route('login');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login');
    }

    public function loginGithub(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * @throws \Exception
     */
    public function handleOAuthLogin(): RedirectResponse
    {
        $this->authService->handleOAuthLogin();

        return redirect()->route('home');
    }
}
