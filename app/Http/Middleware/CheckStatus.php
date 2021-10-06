<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\User;
use App\Events\UserActions;

class CheckStatus
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
        event(new UserActions($request));
        if(Auth::user()->phone_verify == 1 && Auth::user()->email_verify == 1 && Auth::user()->status == 1 && Auth::user()->tfver == 1) {
            return $next($request);
        } else {
            return redirect()->route('user.authorization');
        }
    }
}
