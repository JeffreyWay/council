<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;

class UserConfirmationController extends Controller
{
    public function update()
    {
        $user = User::findOrFail(request()->id);
        $user->confirm();

        if (request()->wantsJson()) {
            return response($user, 201);
        }

        return redirect(route('admin.users.index'))
            ->with('flash', "User $user->name has been confirmed!");
    }
}
