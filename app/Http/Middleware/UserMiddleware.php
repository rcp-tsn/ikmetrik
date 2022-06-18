<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Session;

class UserMiddleware
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
        if (! \Auth::check()) {
            return redirect()->route('root');
        }

        $authUser = \Auth::user();


        return $next($request);
    }
}
