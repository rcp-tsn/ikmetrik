<?php

namespace App\Http\Middleware;

use App\Models\SgkCompany;
use Closure;

class ShouldHaveValidLoginGirisCikisBildirgesi
{

    public function handle($request, Closure $next)
    {
        $selectedCompanyID = session()->get('selectedCompany')['id'];
        $selectedCompany = SgkCompany::find($selectedCompanyID);
        if (strlen($selectedCompany->cookiegirisCikisBildirgesi) > 0 && $selectedCompany->cookiegirisCikisBildirgesi_status == 1) {
            return $next($request);
        }
        $cookieKey = startCookieGirisCikisBildirgesi($selectedCompany);

        return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Needed', 'key' => $cookieKey]);



    }

}
