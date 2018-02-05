<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;

class UserSuspensionController extends Controller
{
    public function destroy(User $user)
    {
        $user->update(['active' => false]);

        return redirect(route('admin.users.index'))
            ->with('flash', "The account of user $user->name has been suspended");
    }

    public function store(User $user)
    {
        $user->update(['active' => true]);

        return redirect(route('admin.users.index'))
            ->with('flash', "The account of user $user->name is now active");
    }
}
