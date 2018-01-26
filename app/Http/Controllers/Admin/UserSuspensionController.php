<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;

class UserSuspensionController extends Controller
{
    public function update()
    {
        $user = User::findOrFail(request()->id);
        $user->toggleActive();

        if (request()->wantsJson()) {
            return response($user, 201);
        }

        $activeStatus = $user->active ? 'active' : 'suspended';

        return redirect(route('admin.users.index'))
            ->with('flash', "User $user->name is $activeStatus!");
    }
}
