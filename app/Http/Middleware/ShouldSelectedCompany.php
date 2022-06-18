<?php

namespace App\Http\Middleware;

use App\Models\SgkCompany;
use Closure;

class ShouldSelectedCompany
{

    public function handle($request, Closure $next)
    {
        if (!session()->has('selectedCompany')) {
            session()->flash('danger', "Devam etmek için kurum seçmelisiniz.");
            return redirect(route('sgk_company_select'));
        }
        $selectedCompanyID = session()->get('selectedCompany')['id'];

        $selectedCompany = SgkCompany::find($selectedCompanyID);

        if (!$selectedCompany) {
            session()->flash('danger', "Devam etmek için kurum seçmelisiniz.");
            return redirect(route('sgk_company_select'));
        }

        return $next($request);
    }
}
