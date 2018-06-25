<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // check if the user email has been verified (the token would be null)
        $user = Auth::user();
        if ($user->verifyToken) {
            return redirect('home');
        }
        return $next($request);
    }
}
