<?php

namespace App\Http\Middleware;

use App\Models\SgkCompany;
use Closure;

class ShouldHaveValidLoginV3
{
    public function handle($request, Closure $next)
    {

        $selectedCompanyID = session()->get('selectedCompany')['id'];
        $selectedCompany = SgkCompany::find($selectedCompanyID);

        if ($selectedCompany->cookiev3 && $selectedCompany->cookiev3_status) {
            return $next($request);
        }

        $cookieKey = startCookieV3($selectedCompany);
        return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Needed', 'key' => $cookieKey]);






    }
}
