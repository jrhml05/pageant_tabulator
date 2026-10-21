<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAccess
{
    /**
     * Only lets `$role` through; anyone else is sent to their own start page.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if ($user->role === $role) {
            return $next($request);
        }

        return redirect()->route($user->isJudge() ? 'judge.app' : 'home');
    }
}
