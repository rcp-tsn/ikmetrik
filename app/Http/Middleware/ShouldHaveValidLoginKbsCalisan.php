<?php

namespace App\Http\Middleware;

use App\Models\SgkCompany;
use Closure;

class ShouldHaveValidLoginKbsCalisan
{

    public function handle($request, Closure $next)
    {
        $selectedCompanyID = session()->get('selectedCompany')['id'];
        $selectedCompany = SgkCompany::find($selectedCompanyID);
        if (strlen($selectedCompany->cookieKbsCalisan) > 0 && $selectedCompany->cookieKbsCalisan_status == 1) {
            return $next($request);
        }
        $cookieKey = startCookieKbsCalisan($selectedCompany);

        return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Needed', 'key' => $cookieKey]);



    }

}
