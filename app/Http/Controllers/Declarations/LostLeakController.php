<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\SgkCompany;
use Illuminate\Http\Request;
use Curl;

class LostLeakController extends Controller
{
    public function index()
    {
        $company = session()->get('selectedCompany');
        $company = SgkCompany::where('id', $company['id'])->first();
        $cookieKey = startCookieV3($company);
        return view('losts.index',compact('cookieKey'));
    }
    public function v2TahakkukDate()
    {
        dd('ok');
    }


}
