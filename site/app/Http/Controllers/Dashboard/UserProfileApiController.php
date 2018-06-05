<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\MyProfileRequest;
use App\Http\Controllers\Controller;

class UserProfileApiController extends Controller
{
    /**
     * возвращает данные пользователя
     */
    public function edit(User $user)
    {
        return $user;
    }

    /**
     * обновляет данные пользователя
     */
    public function update(MyProfileRequest $request, User $user)
    {
        return User::updateProfile($request, $user);
    }
}
