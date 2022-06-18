<?php

namespace App\Http\Middleware;

use App\Models\SgkCompany;
use Closure;

class ShouldHaveValidLoginV2
{

    public function handle($request, Closure $next)
    {
        $selectedCompanyID = session()->get('selectedCompany')['id'];
        $selectedCompany = SgkCompany::find($selectedCompanyID);
        if ($selectedCompany->cookiev2 && $selectedCompany->cookiev2_status) {
            return $next($request);
        }
        $cookieKey = startCookieV2($selectedCompany);

        return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Needed', 'key' => $cookieKey]);



    }

}
