<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
	
/*	public function handle($request, Closure $next, ...$guards)
{
    if (empty($guards)) {
        $guards = [null];
    }

    foreach ($guards as $guard) {
        if ($this->auth->guard($guard)->check()) {
            return $next($request);
        }
    }

    return response()->json(['authenticated' => false], 401);
}*/
}
