<?php

namespace App\Http\Middleware;

use App\Models\SgkCompany;
use Closure;

class ShouldHaveValidLoginIsKazasi
{

    public function handle($request, Closure $next)
    {
        $selectedCompanyID = session()->get('selectedCompany')['id'];
        $selectedCompany = SgkCompany::find($selectedCompanyID);
        if (strlen($selectedCompany->cookieIsKazasi) > 0 && $selectedCompany->cookieIsKazasi_status == 1) {
            return $next($request);
        }
        $cookieKey = startCookieIsKazasi($selectedCompany);

        return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Needed', 'key' => $cookieKey]);



    }

}
