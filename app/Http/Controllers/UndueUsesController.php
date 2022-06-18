<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SgkCompany;
use Illuminate\Http\Request;
use Curl;

class UndueUsesController extends Controller
{
    public function index()
    {
        $company = session()->get('selectedCompany');
        $company = SgkCompany::where('id', $company['id'])->first();
        $cookieKey = startCookieV3($company);
        return view('undue.index', compact('cookieKey'));

    }

    public function return(Request $request)
    {
        $company = session()->get('selectedCompany');
        $company = SgkCompany::where('id', $company['id'])->first();
        $login = sysloginv3($company, $request->captcha);

        if ($login) {
            $cookieFile = cookieFileName($company->cookiev3);
            # BUTONA TIKLATMA BAŞLA
            Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/caridonemTesvikSorgulama_duzeltme.action')
                ->withHeader('Referer: https://uyg.sgk.gov.tr/YeniSistem')
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();
            # BUTONA TIKLATMA BİTİR
            $tokkenData = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkCariDonemTesvikSorgu.action;')
                ->withHeader('Referer: https://uyg.sgk.gov.tr/IsverenSistemi')
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->get();


            preg_match('/isverenToken=(.*?)"/', $tokkenData, $tokken);

            $undue = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/cariDonemTesvikSorgulama.action;')
                ->withHeader('Referer: https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkCariDonemTesvikSorgu.action;')
                ->withData([
                    'isverenToken' => $tokken[1],
                ])
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->get();


        $undue = str_replace('alt=', "title=", $undue);
        $undue = str_replace('background=', "title=", $undue);
//        $undue = str_replace('<td valign="bottom" align="right" width="11"><img src="/YeniSistem/images/content/Border_TopRight.gif" width="11" align="middle" height="22" border="0"></td>', "", $undue);
//        $undue = str_replace('<td background="/YeniSistem/images/content/Border_Bottom.gif"></td>', "", $undue);
//        $undue = str_replace('<img src="/YeniSistem/images/content/Border_BottomRight.gif" border=0>', "", $undue);
//        $undue = str_replace('<img src="/YeniSistem/images/ekran/Border_TopRight.gif" width="11" align="middle" height="22" border="0" class="ieDuzeltUst"/>', "", $undue);

            return view('undue.return', compact('undue'));
        }

    }
}
