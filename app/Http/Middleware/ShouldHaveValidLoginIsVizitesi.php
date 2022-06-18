<?php

namespace App\Http\Middleware;

use App\Models\SgkCompany;
use Closure;

class ShouldHaveValidLoginIsVizitesi
{

    public function handle($request, Closure $next)
    {
        $selectedCompanyID = session()->get('selectedCompany')['id'];
        $selectedCompany = SgkCompany::find($selectedCompanyID);
        if (strlen($selectedCompany->cookieIsVizitesi) > 0 && $selectedCompany->cookieIsVizitesi_status == 1) {
            return $next($request);
        }
        $cookieKey = startCookieIsVizitesi($selectedCompany);

        return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Needed', 'key' => $cookieKey]);



    }

}
