<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfUserIsSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->active) {
            if (request()->expectsJson()) {
                return response()->json(['reason' => 'You are currently suspended with limited access to the forum. Please contact support for assistance.'], 403);
            }
            return redirect('/threads')->with([
                'warning' => 'You are currently suspended with limited access to the forum.',
                'support' => config('council.support.suspension')
            ]);
        }

        return $next($request);
    }
}
