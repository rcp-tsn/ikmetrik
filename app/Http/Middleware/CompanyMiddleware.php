<?php

namespace App\Http\Middleware;

use Closure;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        $returnSuccess = true;
        $user = \Auth::user();

        switch ($permissions) {
            case 'information':
                if (! $user->can('company_information')) {
                    $returnSuccess = false;
                }
                break;
        }

        if (! $returnSuccess) {
            return response(view('errors.403'), 403);
        }

        return $next($request);
    }
}
