<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user(); // Получаем текущего пользователя
        $quests = Quest::where('owner_id', $user->id)->get(); // Получаем квесты вручную

        return Inertia::render('Profile/Profile', [
            'user' => $user,
            'quests' => $quests, // Передаем квесты отдельно
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($request->only('name', 'email'));

        return back()->with('success', 'Профіль оновлено!');
    }
}
