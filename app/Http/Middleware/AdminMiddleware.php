<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Carbon\Carbon;
use Session;

class AdminMiddleware
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

        if (! Auth::check()) {
            return redirect()->route('root');
        }
        return $next($request);
    }
}
