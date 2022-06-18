<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\DeclarationInfo;
use App\Models\Declaration;
use App\Models\DeclarationService;
use App\Models\SgkCompany;
use Carbon\Carbon;
use Curl;
use FilterGenerator;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;


class MetrikBotController extends Controller
{

    public function __construct()
    {

    }

    public function giriscikisBildirgesiStart()
    {
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        return view('reports.giris_cikis_bildirgesi', compact('sgk_company'));
    }

    public function GirisCikisBildirgesiCek()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiegirisCikisBildirgesi);


        $last_date = Declaration::where('sgk_company_id', $sgk_company->id)->orderBy('declarations_date', 'DESC')->first();
        $declarations = Declaration::where('sgk_company_id', $sgk_company->id)->where('declarations_date', $last_date->declarations_date)->pluck('id');
        $declaration_services = DeclarationService::whereIn('declaration_id', $declarations)->distinct('tck')->select('tck')->get();
        $total = count($declaration_services);

        $i = 0;

        foreach($declaration_services as $declaration_service) {
            $getFirst = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/amp/sigortaliTescilAction?jobid=sorgulama')
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->get();
            $response = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/amp/sigortaliTescilAction')

                ->withData([
                    'kimlikno' => $declaration_service->tck,
                    'jobid' => 'sorgula',
                    'tkrVno' => ''
                ])
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();

            $response = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/amp/sigortaliTescilAction')

                ->withData([
                    'kimlikno' => $declaration_service->tck,
                    'jobid' => 'reshowisegiris',
                    'tkrVno' => '0'
                ])
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();
            $getFirst = botPageHtmlCleaner($response);

            if (strstr($getFirst, "Tekrar Login olmak için") || strstr($getFirst, "E-Bildirge HATA")) {


            } else {
                preg_match_all('#<table border="1".*?>(.*?)<\/table>#', $getFirst, $parse);

                $html = '';
                if (isset($parse[1])) {
                    $table = $parse[1];
                    if (isset($table[1])) {
                        $html = $table[1];
                    }
                }
                $rowArray = [];
                preg_match_all('#<tr height="5".*?>(.*?)<\/tr>#', $html, $html_last);
                if (isset($html_last[1])) {
                    foreach ($html_last[1] as $html_row) {
                        preg_match_all('#<td .*?>(.*?)<\/td>#', $html_row, $html_row2);
                        //dd($html_row2);
                        if (isset($html_row2[1][2]) && isset($html_row2[1][1])) {
                            $rowArray[clearText($html_row2[1][1])] = clearText(trim($html_row2[1][2]));
                        }

                    }

                }

                if(count($rowArray) == 9) {
                    $has_tck = DeclarationInfo::where('tck', $declaration_service->tck)->first();
                    if (!$has_tck) {
                        $save = new DeclarationInfo();
                        $save->tck = $declaration_service->tck;
                        $save->isim = $rowArray['Adı'];
                        $save->soyisim = $rowArray['Soyad'];
                        $save->ilk_soyadi = $rowArray['İlk Soyadı'];
                        $save->baba_adi = $rowArray['Baba Adı'];
                        $save->ana_adi = $rowArray['Ana Adı'];
                        $save->dogum_yeri = $rowArray['Doğum Yeri'];
                        $dateExplode = explode(".", $rowArray['Doğum Tarihi']);
                        $birth_date = $dateExplode[2] . "-" . $dateExplode[1] . "-" . $dateExplode[0]; //

                        $save->birth_date = $birth_date;
                        $save->uyruk = $rowArray['Yabancı Uyruklu ise Ülke Adı'];
                        $save->education = $rowArray['Öğrenim durumu'];
                        $save->save();
                        $i++;
                    }

                }
            }


        }

        return response()
            ->json([
                'code' => 'FINISH',
                'message' => $total.' adet personelden '.$i.' sı için giriş çıkış bildirgelerinden veriler alınmıştır',
                'progress' => 100,
                'url' => route('declarations.incentives.giris-cikis-bildirgesi-start')
            ]);

    }

    public function GirisCikisBildirgesiPost(Request $request)
    {
        $sgk_company = getSgkCompany();
        $captcha = $request->captcha;

        $result = sysloginGirisCikisBildirgesi($sgk_company, $captcha);

        if ($result) {
            return response()->json(['code' => 'LOGIN_OK', 'message' => 'Login Success']);
        } else {
            $cookieKey = startCookieGirisCikisBildirgesi($sgk_company);
            return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Fail', 'key' => $cookieKey]);
        }
    }

}
