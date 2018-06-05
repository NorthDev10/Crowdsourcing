<?php

namespace App\Http\Controllers\Dashboard;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserProfileController extends Controller
{
    /**
     * Возвращает страницу личного профиля
     */
    public function index()
    {
        $user = User::getMyProfile();

        return view('Dashboard.myProfile', [
            'user' => $user->toArray(),
        ]);
    }

    /**
     * Возвращает страницу пользователя
     */
    public function show(User $user)
    {       
        return view('Dashboard.userProfile', [
            'user' => $user->toArray()
        ]);
    }

    /**
     * возвращает страницу редактирования профиля
     */
    public function edit(int $userId)
    {
        if($userId == Auth::user()->id) {
            return view('Dashboard.myProfileEdit', [
                'userId' => Auth::user()->id,
            ]);
        } else {
            abort(404);
        }
    }
}
