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

        if (request()->expectsJson()) {
            return response($user, 201);
        }

        $activeStatus = $user->active ? 'active' : 'suspended';

        return redirect(route('admin.users.index'))
            ->with('flash', "User $user->name is $activeStatus!");
    }

    public function destroy(User $user)
    {
        $user->update(['active' => false ]);

        return redirect(route('admin.users.index'))
            ->with('flash', "The account of user $user->name has been suspended");

    }

    public function store(User $user)
    {
        $user->update(['active' => true ]);

        return redirect(route('admin.users.index'))
            ->with('flash', "The account of user $user->name is now active");
    }
}
