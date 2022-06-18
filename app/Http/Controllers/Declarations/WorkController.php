<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\ApprovedIncentive;
use App\Models\Declaration;
use App\Models\DeclarationService;
use App\Models\DisabledIncentive;
use App\Models\GainIncentive;
use App\Models\Incentive;
use App\Models\IncentiveService;
use App\Models\SgkCompany;
use Carbon\Carbon;
use Curl;
use FilterGenerator;
use DateTime;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Storage;
use Response;


class WorkController extends Controller
{

    public function __construct()
    {

    }

    public function test()
    {
        $sgk_company = getSgkCompany();
        $return = startCookieV2($sgk_company, $sgk_company->cookiev2);
        return $return;
        /*
        $sgk_company = getSgkCompany();
        $return = startCookieV3($sgk_company, $sgk_company->cookiev3);
        $return = getCaptcha($return);
        return $return;
        */
    }

    public function optionsForm()
    {
        session()->forget('start_with');
        session()->forget('options_laws');
        return view('incentives.incentive_options');
    }

    public function optionsStore(Request $request)
    {
        -
        session(["start_with" => $request->start]);

        $a = $request->options;
        if (in_array('17103', $a)) {
            $a[] = '27103';
        }
        session(["options_laws" => $a]);

        if ($request->start == 'HAKEDIS') {
            return redirect(route('declarations.incentives.gain-start'));
        } else {
            return redirect(route('declarations.incentives.main'));
        }

    }

    public function main()
    {
        session()->forget('excel_users');
        session()->forget('tanimlamalara_bakilacakmi');

        $option_laws_text = '';
        if (session()->has('options_laws')) {
            $options_laws = session()->get('options_laws');
            $option_laws_text = implode(', ', $options_laws);
        } else {
            return redirect(route('declarations.incentives.options.form'));
        }
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        return view('incentives.main', compact('sgk_company', 'option_laws_text'));
    }

    public function gainStart()
    {
        session()->forget('excel_users');


        $option_laws_text = '';
        if (session()->has('options_laws')) {
            $options_laws = session()->get('options_laws');
            $option_laws_text = implode(', ', $options_laws);
        } else {
            return redirect(route('declarations.incentives.options.form'));
        }

        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        $current_declaration = Declaration::where('sgk_company_id', $sgk_company->id)->orderBy('declarations_date', 'DESC')->first();
        if (!empty($current_declaration->declarations_date)) {
            $current_declarations = Declaration::where('sgk_company_id', $sgk_company->id)->where('declarations_date', $current_declaration->declarations_date)->pluck('id');
        }

        if ($current_declaration) {
            $declaration_services = DeclarationService::whereIn('declaration_id', $current_declarations)->whereNotNull('job_start')->get();
        } else {
            $declaration_services = null;

        }
        return view('incentives.gain', compact('sgk_company', 'option_laws_text', 'declaration_services'));
    }

    public function potential()
    {
        $sgk_company = getSgkCompany();


        if (session()->has('potential_icitement_result')) {
            $potential_icitement_result = session()->get('potential_icitement_result');
        } else {
            $potential_icitement_result = [];
        }
        session()->forget('potential_icitement_result');
        return view('incentives.potential_incentives', compact('sgk_company', 'potential_icitement_result'));
    }

    public function metrik()
    {
        session()->forget('excel_users');
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        return view('incentives.metrik', compact('sgk_company'));
    }

    public function mainV2()
    {
        session()->forget('excel_users');
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        return view('incentives.mainV2', compact('sgk_company'));
    }

    public function gainStart7252()
    {
        session()->forget('excel_users');
        session(["do_7252" => true]);//7252 ile tarama yapılabilecek
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        return view('incentives.gain', compact('sgk_company'));
    }

    public function v2BildirgeCek()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev2);
        $getFirst = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonayBekleyenTahakkuklar.action')
            ->withHeader('Referer: https://ebildirge.sgk.gov.tr/EBildirgeV2')
            ->withData([
                'struts.token.name' => 'token',
                'token' => 'AAQFQRFMRAHDU1XOIKTE9ERQNFJEB1NG'
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        $getFirst = botPageHtmlCleaner($getFirst);
        if (strstr($getFirst, "Kullanıcı Bilgilerini Giriniz.")) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.2',
                    'step' => 'v2_bildirge',
                ]);
        }
        if (strstr($getFirst, "Onaylanacak Bildirge Bulunamadı")) {
            return response()
                ->json([
                    'code' => 'ERROR',
                    'message' => 'Onaylanacak bildirge bulunamadı!',
                ]);
        }

        preg_match_all("#<tr class='yesil'.*?title=''>(.*?)<\/tr>#", $getFirst, $parseBildirgeler);

        $bildirgeler = [];
        foreach ($parseBildirgeler[1] as $parseBildirge) {
            $desen = '#<p>(.*?)</p>#';
            preg_match_all($desen, $parseBildirge, $pParse);
            $bildirge = $pParse[1];
            preg_match('/value="(.*?)"/', $bildirge[0], $value);
            $bildirge[0] = trim($value[1]);
            if (strstr($bildirge[6], "-")) {
                $explodeLaw = explode("-", $bildirge[6]);
                $bildirge[6] = trim($explodeLaw[0]);
            } elseif (!is_numeric(trim($bildirge[6]))) {
                $bildirge[6] = 0;
            }
            $bildirgeler[] = $bildirge;
        }
        foreach ($bildirgeler as $bildirge) {

            $dateExplode = explode("/", $bildirge[3]);
            $declarations_date = $dateExplode[0] . "-" . $dateExplode[1] . "-01"; // declarations_date
            $declaration = Declaration::where('genus', $bildirge[4])
                ->where('document_type', $bildirge[1])
                ->where('law', $bildirge[6])
                ->where('declarations_date', $declarations_date)
                ->where('sgk_company_id', number_format(session()->get('selectedCompany')['id']))
                ->where('data', $bildirge[0])
                ->first();
            if (empty($declaration)) {
                $declaration = new Declaration();
                $declaration->sgk_company_id = number_format(session()->get('selectedCompany')['id']);
                $declaration->law = $bildirge[6];
                $declaration->declarations_date = $declarations_date;
                $declaration->genus = $bildirge[4];
                $declaration->document_type = $bildirge[1];
                $declaration->data = $bildirge[0];
                $declaration->save();
            }
        }

        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => "V2'den bildirgeler çekildi!",
                'progress' => 10,
                'step' => 'v2_bildirge_parse',
            ]);
    }


    public function v2BildirgeParse()
    {
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev2);
        $declarations = Declaration::where("sgk_company_id", $sgk_company->id)
            ->orderby('declarations_date', 'DESC')
            ->get()
            ->groupBy('declarations_date');
        foreach ($declarations->first() as $declaration) {
            $fileName = $sgk_company->company_username . DIRECTORY_SEPARATOR . $declaration->declarations_date . ".pdf";
            $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tilesislemTamam.action')
                ->withHeader('Referer: https://ebildirge.sgk.gov.tr/EBildirgeV2')
                ->withData([
                    'bildirgeRefNo' => $declaration->data . "  ",
                    'download' => true,
                    'action:tahakkukfisHizmetPdf' => 'Hizmet Listesi(PDF)',
                ])
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();
            Storage::disk("pdfs_bildirge")->put($fileName, $response);
            $readfile = storage_path('app' . DIRECTORY_SEPARATOR . 'pdfs_bildirge' . DIRECTORY_SEPARATOR . $sgk_company->company_username . DIRECTORY_SEPARATOR . $declaration->declarations_date . '.pdf');
            $pdf = new Pdf($readfile);
            $html = $pdf->html();
            for ($i = 1; $i <= $pdf->getPages(); $i++) {
                $pdf = new Pdf($readfile);
                $html = $pdf->html();
                $html = $html->goToPage($i);
                preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);
                $personels = [];
                foreach ($html[1] as $html1_key => $html_1) {
                    $personels[$html_1][] = $html[2][$html1_key];
                }
                foreach ($personels as $key => $personel) {
                    $year_array = explode('-', $declaration->declarations_date);
                    if (isset($year_array[0])) {
                        $year = $year_array[0];
                    } else {
                        $year = date('Y');
                    }
                    if (count($personel) > 10) {
                        if (trim($personel[8]) != 0) {
                            if (strlen($personel[8]) == 4) {
                                $job_start = $year . "-" . str_split($personel[8])[2] . str_split($personel[8])[3] . "-" . str_split($personel[8])[0] . str_split($personel[8])[1];
                            } elseif (strlen($personel[8]) == 3) {
                                $job_start = $year . "-" . str_split($personel[8])[1] . str_split($personel[8])[2] . "-0" . str_split($personel[8])[0];
                            }
                        } else {
                            $job_start = null;
                        }
                        $plus = 0;
                        if (count($personel) == 14) {
                            $plus = 1;
                        }
                        $control = DeclarationService::where("declaration_id", $declaration->id)->select('id')
                            ->where("tck", $personel[1])
                            ->get()->count();
                        if ($control == 0) {
                            $save = new DeclarationService();
                            $save->declaration_id = $declaration->id;
                            $save->tck = $personel[1];
                            $save->isim = $personel[2];
                            $save->soyisim = $personel[3];
                            $save->job_start = $job_start;
                            if (count($personel) == 14) {
//                            $save->kizlik_soyadi = $personel[4];
                            }
                            $save->ucret_tl = decimalFormat($personel[4 + $plus]);
                            $save->ikramiye_tl = decimalFormat($personel[5 + $plus]);
                            $save->gun = $personel[6 + $plus];
                            if (isset($personel[12 + $plus])) {
                                $save->meslek_kod = $personel[12 + $plus];
                            } else {
                            }
                            $save->save();
                        }
                    }
                }
            }
        }

        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => "Bildirgeler kaydedildi!",
                'progress' => 20,
                'step' => 'v2_tahakkuk_list',
            ]);

    }


    public function excelUpload(Request $request)
    {
        session(['errorss' => null]);
        if (strtolower($request->excel->getClientOriginalExtension()) == "xls") {
            $excel = \SimpleXLS::parse($request->excel);
        } elseif (strtolower($request->excel->getClientOriginalExtension()) == "xlsx") {
            $excel = \SimpleXLSX::parse($request->excel);
        }
        $xls = $excel->rows();

        if (count($xls[0]) == 26) {
            unset($xls[0]);
            foreach ($xls as $key => $row) {
                # FILTERS
                // TL VARSA SİL
                $xls[$key][15] = trim(str_replace("TL", "", $row[15]));
                // Meslek No Format
                $xls[$key][22] = trim(str_replace(",", ".", $row[22]));
                // Law No Replace
                if (trim($row[3]) == 0611 or trim($row[3]) == 27103) {
                    $xls[$key][3] = 5510;
                }
                # FILTERS END
                $arr = [];
                $arr[] = 'A';
                $arr[] = trim($row[2]);
                $arr[] = trim($row[3]);
                $arr[] = trim($row[4]);
                $arr[] = trim($row[5]);
                $arr[] = trim($row[6]);
                $arr[] = trim($row[7]);
                $arr[] = trim($row[8]);
                $arr[] = trim($row[9]);
                $arr[] = ConvertToUTF8($row[10]);
                $arr[] = ConvertToUTF8($row[11]);
                $arr[] = trim($row[12]);
                $arr[] = trim($row[14]);
                $arr[] = trim($row[15]);
                $arr[] = trim($row[16]);
                if (!empty($row[17])) {
                    $arr[] = str_split($row[17])[0] . str_split($row[17])[1];
                    $arr[] = str_split($row[17])[2] . str_split($row[17])[3];
                } else {
                    $arr[] = null;
                    $arr[] = null;
                }
                if (!empty($row[18])) {
                    $arr[] = str_split($row[18])[0] . str_split($row[18])[1];
                    $arr[] = str_split($row[18])[2] . str_split($row[18])[3];
                } else {

                    $arr[] = null;
                    $arr[] = null;
                }
                $arr[] = trim($row[19]);
                $arr[] = trim($row[20]);
                $arr[] = trim($row[21]);
                $arr[] = trim($row[22]);
                $arr[] = trim($row[23]);
                $arr[] = trim($row[24]);
                $arr[] = trim($row[25]);
                $arr[] = trim($row[26]);
                if (session()->has('excel_users')) {
                    $arrX = session()->get('excel_users');
                } else {
                    $arrX = [];
                }
                $arrX[] = $arr;
                session(['excel_users' => $arrX]);
            }


        } elseif (count($xls[0]) == 32) {
            unset($xls[0]);
            foreach ($xls as $key => $row) {
                # FILTERS
                // TL string delete (Hak Edilen Ücret)
                $row[13] = trim(str_replace("TL", "", $row[13]));
                $row[13] = trim(decimalFormatExcel($row[13], true));
                $row[14] = trim(decimalFormatExcel($row[14], true));
                // Meslek No Format
                $row[22] = trim(str_replace(",", ".", $row[22]));


                $row[10] = ConvertToUTF8($row[10]);
                $row[11] = ConvertToUTF8($row[11]);

                // Law No Replace
                if (trim($row[3]) == 0611 or trim($row[3]) == 27103) {
                    $row[3] = 5510;
                }
                # FILTERS END

                if (session()->has('excel_users')) {
                    $arrX = session()->get('excel_users');
                } else {
                    $arrX = [];
                }
                $arrX[] = $row;
                session(['excel_users' => $arrX]);
            }
        } elseif (count($xls[0]) == 34) {
            unset($xls[0]);
            foreach ($xls as $key => $row) {
                unset($row[0]);
                unset($row[13]);
                $row = array_values($row);
                $row[13] = trim(str_replace("TL", "", $row[13]));
                $row[14] = trim(str_replace("TL", "", $row[14]));
                $row[22] = trim(str_replace(",", ".", $row[22]));
                $row[13] = trim(decimalFormatExcel($row[13], true));
                $row[14] = trim(decimalFormatExcel($row[14], true));
                if (session()->has('excel_users')) {
                    $arrX = session()->get('excel_users');
                } else {
                    $arrX = [];
                }
                $arrX[] = $row;
                session(['excel_users' => $arrX]);
            }
        } elseif (count($xls[0]) == 12) {
            unset($xls[0]);
            foreach ($xls as $key => $row) {

                $row = array_values($row);
                $row[5] = trim(str_replace("TL", "", $row[5]));
                $row[6] = trim(str_replace("TL", "", $row[6]));
                $row[5] = trim(decimalFormatExcel($row[5], true));
                $row[6] = trim(decimalFormatExcel($row[6], true));
                if (session()->has('excel_users')) {

                    $arrX = session()->get('excel_users');
                } else {
                    $arrX = [];
                }
                $arrX[] = $row;
                //dd($arrX);

                session(['excel_users' => $arrX]);
            }

        }

        $newPersonel = [];

        foreach (session()->get('excel_users') as $excel_user) {
            if ($excel_user[0] != "") {
                $newPersonel[] = $excel_user;
            }
        }
        //dd($newPersonel);
        session(['excel_users' => $newPersonel]);
        return response()->json([
            'ver' => "1.0",
            'ret' => true,
            'errmsg' => null,
            'errcode' => 0,
            'data' => [
                'status' => 'success',
                'count' => count(session()->get('excel_users')),
            ]
        ]);
    }


    public function excelSet()
    {

        session(['iskur_errors'=>[]]);
        $sgk_company_id = session()->get('selectedCompany')['id'];

        $excel_users = session()->get('excel_users');


        if (count($excel_users[0]) == 12) {
            foreach ($excel_users as $excel_user) {

                if (trim($excel_user[4]) == session()->get('selectedCompany')['registry_id']) {
                    $declarations_date = Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'); // declarations_date

                    $declaration = Declaration::where('genus', '0' . $excel_user[9])
                        ->where('document_type', 'A')
                        ->where('law', $excel_user[8])
                        ->where('declarations_date', $declarations_date)
                        ->where('sgk_company_id', $sgk_company_id)
                        ->first();


                    if (empty($declaration)) {
                        $declaration = new Declaration();
                        $declaration->sgk_company_id = session()->get('selectedCompany')['id'];
                        $declaration->law = $excel_user[8];
                        $declaration->declarations_date = $declarations_date;
                        $declaration->document_type = 'A';
                        $declaration->genus = '0' . $excel_user[9];
                        $declaration->save();
                    }
                    $declaration_ids[] = $declaration->id;

                    if (DeclarationService::where('declaration_id', $declaration->id)->where('tck', $excel_user[0])->get()->count() == 0 and $excel_user[9] != 22) {
                        $year_array = explode('-', $declaration->declarations_date);
                        if (isset($year_array[0])) {
                            $year = $year_array[0];
                            $gun = $year_array[2];
                            $ay = $year_array[1];
                        }
                        if (trim($excel_user[10]) != "" or trim($excel_user[11]) != "") {
                            if (trim($excel_user[10]) != 0 or trim($excel_user[11]) != 0) {
                                if (trim($excel_user[10]) != 0 or trim($excel_user[10]) != "") {
                                    $job_start = $year . "-" . $ay . "-" . $excel_user[10];
                                } else {
                                    $job_start = null;
                                }

                            } else {
                                $job_start = null;
                            }

                        } else {
                            $job_start = null;
                        }

                        DeclarationService::create([
                            'declaration_id' => $declaration->id,
                            'tck' => $excel_user[0],
                            'sg_sicil_no' => $excel_user[0],
                            'isim' => $excel_user[1],
                            'soyisim' => $excel_user[2],
                            'ucret_tl' => $excel_user[5],
                            'ikramiye_tl' => $excel_user[6],
                            'gun' => $excel_user[7],
                            'job_start' => $job_start,
                            'meslek_kod' => 0,
                        ]);

                    }
                } else {
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => "Excel'den veriler alınamadı Firma İş Yeri Numarası İle Exceldeki Tutmadı!",
                            'progress' => 1,
                            'step' => '',
                        ]);
                }
            }

        } else {
            foreach ($excel_users as $excel_user) {

                if (trim($excel_user[5]) == session()->get('selectedCompany')['registry_id']) {


                    $declarations_date = $excel_user[26] . "-" . $excel_user[25] . "-01"; // declarations_date
                    if (!empty($excel_user[26])) {

                        $declaration = Declaration::where('sgk_company_id', '=', $sgk_company_id)->where('genus', $excel_user[0])
                            ->where('document_type', $excel_user[1])
                            ->where('law', $excel_user[2])
                            ->where('declarations_date', $declarations_date)
                            ->where('document_type', $excel_user[1])
                            ->first();

                        if (empty($declaration)) {
                            $declaration = new Declaration();
                            $declaration->sgk_company_id = $sgk_company_id;
                            $declaration->law = $excel_user[2];
                            $declaration->declarations_date = $declarations_date;
                            $declaration->genus = $excel_user[0];
                            $declaration->document_type = $excel_user[1];
                            $declaration->save();
                        }
                        $declaration_ids[] = $declaration->id;
                        if (DeclarationService::where('declaration_id', $declaration->id)->where('tck', $excel_user[9])->get()->count() == 0 and $excel_user[1] != 22) {
                            $year_array = explode('-', $declaration->declarations_date);
                            if (isset($year_array[0])) {
                                $year = $year_array[0];
                            } else {
                                $year = date('Y');
                            }
                            if (trim($excel_user[15]) != "" or trim($excel_user[16]) != "") {
                                if (trim($excel_user[15]) != 0 or trim($excel_user[16]) != 0) {
                                    $job_start = $year . "-" . $excel_user[16] . "-" . $excel_user[15];
                                } else {
                                    $job_start = null;
                                }

                            } else {
                                $job_start = null;
                            }

                            DeclarationService::create([
                                'declaration_id' => $declaration->id,
                                'tck' => $excel_user[9],
                                'sg_sicil_no' => $excel_user[8],
                                'isim' => $excel_user[10],
                                'soyisim' => $excel_user[11],
                                'ucret_tl' => $excel_user[13],
                                'ikramiye_tl' => $excel_user[14],
                                'gun' => intval($excel_user[12]),
                                'job_start' => $job_start,
                                'meslek_kod' => $excel_user[22],
                            ]);

                        }
                    }
                    session(['declaration_ids' => $declaration_ids]);
                } else {
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => "Excel'den veriler alınamadı!",
                            'progress' => 1,
                            'step' => '',
                        ]);
                }
            }

        }

        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => "Excel'den personeller ayıklanıp sisteme eklendi!",
                'progress' => 10,
                'step' => 'v2_tahakkuk_list',
            ]);
    }

    public function v2TahakkukListMetrik()
    {
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev2);
        $lastMounth = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemBilgileriniYukle.action')
            ->withData([
                'struts.token.name' => 'token',
                'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT'
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        $lastMounth = botPageHtmlCleaner($lastMounth);
        if (empty($sgk_company->founded)) {
            preg_match('/<td class="p10">(.*?)<\/td>/', $lastMounth, $founded);
            if (!isset($founded[1])) {
                SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
                return response()
                    ->json([
                        'code' => 'LOGIN_FAIL',
                        'message' => '1-Tahakkuk listeleri çekilemedi.',
                        'step' => 'v2_metrik_tahakkuk_list'
                    ]);
            }
            $expFounded = explode("/", $founded[1]);
            $founded = $expFounded[2] . "-" . $expFounded[1] . "-" . $expFounded[0];
            SgkCompany::where('id', $sgk_company->id)->update(['founded' => $founded]);
        }

        preg_match_all('#<option value="(.*?)">.*?<\/option>#', $lastMounth, $dates);
        $dateImplode = implode(",", $dates[1]);
        $dateExplode = explode("-1", $dateImplode);
        if (!isset($dateExplode[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => '2-Tahakkuk listeleri çekilemedi.',
                    'step' => 'v2_metrik_tahakkuk_list'
                ]);
        }
        $dateExplode = explode(",", $dateExplode[1]);
        $dateExplode = array_filter($dateExplode);
        $lastMounth = count($dateExplode);
        session(["lastMounth" => $lastMounth]);
        $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemSecildi.action')
            ->withData([
                'struts.token.name' => 'token',
                'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                'hizmet_yil_ay_index' => $lastMounth,
                'hizmet_yil_ay_index_bitis' => '1'
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();
        $desen = '#<td align="center"><p>(.*?)</p></td>|<td align="right"><p>(.*?)</p></td>#';
        preg_match_all($desen, $response, $arr1);
        $arr1[2] = array_filter($arr1[2], 'strlen');
        $arr1[1] = array_filter($arr1[1], 'strlen');
        $arrNew = array_replace($arr1[1], $arr1[2]);
        $arrNew = array_filter($arrNew, 'strlen');
        ksort($arrNew);
        preg_match_all("/islem\(('H','(.*?)')\)/u", $response, $arr2);
        $arrNew = array_chunk($arrNew, 8);
        $arrList = [];
        foreach ($arrNew as $key => $items) {
            $list = [];
            $i = 0;
            foreach ($items as $item) {
                if ($i == 0 or $i == 1) {
                    $origDate = trim($item) . "/01";
                    $origDate = str_replace(" ", "", $origDate);
                    $date = str_replace('/', '-', $origDate);
                    $list[] = date("Y-m-d", strtotime($date));
                } else {
                    $list[] = trim($item);
                }
                $i++;
            }
            $list[] = $arr2[2][$key];
            $arrList[] = $list;
        }

        foreach ($arrList as $value) {
            $control = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->where('document_no', '=', $value[8])->select('id')->first();
            if (!$control) {
                $date1 = strtotime($value[0]);
                $date2 = strtotime('2011-03-01');
                //$day = ($date1 >= $date2) ;
                if ($date1 >= $date2) {
                    $save = new ApprovedIncentive();
                    $save->sgk_company_id = $sgk_company->id;
                    $save->accrual = $value[0];
                    $save->service = $value[1];
                    $save->document_type = number_format($value[2]);
                    $save->genus = $value[3];
                    $save->law_no = ($value[4] != '&nbsp;' ? $value[4] : 0);
                    $save->total_staff = $value[5];
                    $save->total_day = $value[6];
                    $save->total_salary = $value[7];
                    $save->document_no = $value[8];
                    $save->save();
                }
            }
        }
        if (count($arrList) != 0) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'Tahakkuk listeleri kaydedildi.',
                    'progress' => 20,
                    'step' => 'v2_pdf_download_metrik',
                ]);
        }
    }

    public function v2TahakkukList()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev2);
        $lastMounth = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemBilgileriniYukle.action')
            ->withData([
                'struts.token.name' => 'token',
                'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT'
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        $lastMounth = botPageHtmlCleaner($lastMounth);

        if (empty($sgk_company->founded)) {
            preg_match('/<td class="p10">(.*?)<\/td>/', $lastMounth, $founded);

            if (!isset($founded[1])) {
                SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
                return response()
                    ->json([
                        'code' => 'LOGIN_FAIL',
                        'message' => '1-Tahakkuk listeleri çekilemedi.',
                        'step' => 'v2_tahakkuk_list'
                    ]);
            }
            $expFounded = explode("/", $founded[1]);
            $founded = $expFounded[2] . "-" . $expFounded[1] . "-" . $expFounded[0];
            SgkCompany::where('id', $sgk_company->id)->update(['founded' => $founded]);
        }

        preg_match_all('#<option value="(.*?)">.*?<\/option>#', $lastMounth, $dates);

        $dateImplode = implode(",", $dates[1]);
        $dateExplode = explode("-1", $dateImplode);
        if (!isset($dateExplode[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => '2-Tahakkuk listeleri çekilemedi.',
                    'step' => 'v2_tahakkuk_list'
                ]);
        }
        $dateExplode = explode(",", $dateExplode[1]);

        $dateExplode = array_filter($dateExplode);

        $lastMounth = count($dateExplode);
        session(["lastMounth" => $lastMounth]);
        $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemSecildi.action')
            ->withData([
                'struts.token.name' => 'token',
                'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                'hizmet_yil_ay_index' => $lastMounth,
                'hizmet_yil_ay_index_bitis' => '1'
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();

        $desen = '#<td align="center"><p>(.*?)</p></td>|<td align="right"><p>(.*?)</p></td>#';
        preg_match_all($desen, $response, $arr1);
        $arr1[2] = array_filter($arr1[2], 'strlen');
        $arr1[1] = array_filter($arr1[1], 'strlen');
        $arrNew = array_replace($arr1[1], $arr1[2]);
        $arrNew = array_filter($arrNew, 'strlen');
        ksort($arrNew);
        preg_match_all("/islem\(('H','(.*?)')\)/u", $response, $arr2);

        $arrNew = array_chunk($arrNew, 8);
        $arrList = [];

        foreach ($arrNew as $key => $items) {
            $list = [];
            $i = 0;
            foreach ($items as $item) {
                if ($i == 0 or $i == 1) {
                    $origDate = trim($item) . "/01";
                    $origDate = str_replace(" ", "", $origDate);
                    $date = str_replace('/', '-', $origDate);
                    $list[] = date("Y-m-d", strtotime($date));
                } else {
                    $list[] = trim($item);
                }
                $i++;
            }
            $list[] = $arr2[2][$key];
            $arrList[] = $list;
        }

        foreach ($arrList as $value) {
            $control = ApprovedIncentive::where('document_no', '=', $value[8])->select('id')->first();

            if (!$control) {
                $date1 = strtotime($value[0]);
                $date2 = strtotime('2011-03-01');
                //$day = ($date1 >= $date2) ;
                if ($date1 >= $date2) {
                    $save = new ApprovedIncentive();
                    $save->sgk_company_id = $sgk_company->id;
                    $save->accrual = $value[0];
                    $save->service = $value[1];
                    $save->document_type = number_format($value[2]);
                    $save->genus = $value[3];
                    $save->law_no = ($value[4] != '&nbsp;' ? $value[4] : 0);
                    $save->total_staff = $value[5];
                    $save->total_day = $value[6];
                    $save->total_salary = $value[7];
                    $save->document_no = $value[8];
                    $save->save();
                }
            }
        }
        if (count($arrList) != 0) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'Tahakkuk listeleri kaydedildi.',
                    'progress' => 20,
                    'step' => 'v2_pdf_download',
                ]);
        } else {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);

            //yeni açılan firma ise v2 direkt atlatmak için koydum
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'Tahakkuk listeleri okundu.',
                    'progress' => 40,
                    'step' => 'v3_new_request',
                ]);
            /*
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => '3-Tahakkuk listeleri çekilemedi.',
                    'step' => 'v2_tahakkuk_list',
                ]);
            */
        }
    }

    public function v2PdfDownloadMetrik()
    {
        $timeOutLimit = rand(10, 15);
        $limit = 0;
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev2);
        $ApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->whereIn('pdf_download', [0, 3])
            ->get();
        foreach ($ApprovedIncentive as $value) {
            $fileName = $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $value->document_no . DIRECTORY_SEPARATOR . $value->document_no . ".pdf";
            $fileStatus = 0;
            if ($value->file_download == 0) {
                $fileControl = Storage::disk('pdfs')->exists($fileName);
                if ($fileControl) {
                    $size = Storage::disk('pdfs')->size($fileName);
                    if ($size >= 20) {
                        $fileStatus = 1;
                    }
                }
            } else {
                $fileStatus = 0;
            }
            if ($fileStatus == 0) {
                $limit++;
                if ($limit > $timeOutLimit) {
                    $progress = session()->get('progress') + 1;
                    session(["progress" => $progress]);
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => 'Tahakkuk listeleri indiriliyor.',
                            'progress' => $progress,
                            'step' => 'v2_pdf_download_metrik',
                        ]);
                }
                $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/pdfGosterim.action')
                    ->withData([
                        'struts.token.name' => 'token',
                        'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                        'tip' => 'tahakkukonayliFisHizmetPdf',
                        'download' => 'false',
                        'hizmet_yil_ay_index' => session()->get('lastMounth'),
                        'hizmet_yil_ay_index_bitis' => '1',
                        'bildirgeRefNo' => $value->document_no
                    ])
                    ->allowRedirect()
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->post();
                Storage::disk("pdfs")->put($fileName, $response);
                $fileStatus = 1;
            }
            // Save Status
            $change = ApprovedIncentive::find($value->id);
            $change->pdf_download = $fileStatus;
            $change->save();
        }


        //kazanç hesaplamak için
        $timeOutGainLimit = rand(10, 15);
        $gainLimit = 0;
        $month = Carbon::now()->subMonth(2);
        $search_date = $month->format('Y-m') . '-01';
        //echo $search_date;die();
        $gainApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->whereIn('gain_download', [0, 3])
            ->whereIn('law_no', [17103, 27103, 6111, 5510, 14857, 7252])
            ->where('accrual', '>=', '2020-01-01')
            ->get();
        foreach ($gainApprovedIncentive as $gain_value) {
            $gainFileName = $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $gain_value->document_no . DIRECTORY_SEPARATOR . $gain_value->document_no . "_gain.pdf";
            $gainFileStatus = 0;
            if ($gain_value->gain_download == 0) {
                $fileControl = Storage::disk('pdfs')->exists($gainFileName);
                if ($fileControl) {
                    $size = Storage::disk('pdfs')->size($gainFileName);
                    if ($size >= 20) {
                        $gainFileStatus = 0;
                    }
                }
            } else {
                $gainFileStatus = 0;
            }
            if ($gainFileStatus == 0) {
                $gainLimit++;
                if ($gainLimit > $timeOutGainLimit) {
                    $progress = session()->get('progress') + 1;
                    session(["progress" => $progress]);
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => 'Tahakkuk listeleri indiriliyor.',
                            'progress' => $progress,
                            'step' => 'v2_pdf_download_metrik',
                        ]);
                }
                $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/pdfGosterim.action')
                    ->withData([
                        'struts.token.name' => 'token',
                        'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                        'tip' => 'tahakkukonayliFisTahakkukPdf',
                        'download' => 'false',
                        'hizmet_yil_ay_index' => session()->get('lastMounth'),
                        'hizmet_yil_ay_index_bitis' => '1',
                        'bildirgeRefNo' => $gain_value->document_no
                    ])
                    ->allowRedirect()
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->post();
                Storage::disk("pdfs")->put($gainFileName, $response);
                $gainFileStatus = 1;
            }
            // Save Status
            $change = ApprovedIncentive::find($gain_value->id);
            $change->gain_download = $gainFileStatus;
            $change->save();
        }
        if ($ApprovedIncentive->count() == 0 && $gainApprovedIncentive->count() == 0) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'Tahakkuk listeleri indirildi.',
                    'progress' => 30,
                    'step' => 'v2_pdf_parse_metrik',
                ]);
        } else {
            $progress = session()->get('progress') + 1;
            session(["progress" => $progress]);
            return response()
                ->json([
                    'code' => 'TIMEOUT',
                    'message' => 'Tahakkuk listeleri indiriliyor.',
                    'progress' => $progress,
                    'step' => 'v2_pdf_download_metrik',
                ]);
        }
    }


    public function v2PdfDownload()
    {
        $timeOutLimit = rand(10, 15);
        $limit = 0;
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev2);
        $ApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->whereIn('pdf_download', [0, 3])
            ->get();
        foreach ($ApprovedIncentive as $value) {
            $fileName = $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $value->document_no . DIRECTORY_SEPARATOR . $value->document_no . ".pdf";
            $fileStatus = 0;
            if ($value->file_download == 0) {
                $fileControl = Storage::disk('pdfs')->exists($fileName);
                if ($fileControl) {
                    $size = Storage::disk('pdfs')->size($fileName);
                    if ($size >= 20) {
                        $fileStatus = 1;
                    }
                }
            } else {
                $fileStatus = 0;
            }
            if ($fileStatus == 0) {
                $limit++;
                if ($limit > $timeOutLimit) {
                    $progress = session()->get('progress') + 1;
                    session(["progress" => $progress]);
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => 'Tahakkuk listeleri indiriliyor.',
                            'progress' => $progress,
                            'step' => 'v2_pdf_download',
                        ]);
                }
                $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/pdfGosterim.action')
                    ->withData([
                        'struts.token.name' => 'token',
                        'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                        'tip' => 'tahakkukonayliFisHizmetPdf',
                        'download' => 'false',
                        'hizmet_yil_ay_index' => session()->get('lastMounth'),
                        'hizmet_yil_ay_index_bitis' => '1',
                        'bildirgeRefNo' => $value->document_no
                    ])
                    ->allowRedirect()
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->post();
                Storage::disk("pdfs")->put($fileName, $response);
                $fileStatus = 1;
            }
            // Save Status
            $change = ApprovedIncentive::find($value->id);
            $change->pdf_download = $fileStatus;
            $change->save();
        }


        //kazanç hesaplamak için
        $timeOutGainLimit = rand(10, 15);
        $gainLimit = 0;
        $month = Carbon::now()->subMonth(2);
        $search_date = $month->format('Y-m') . '-01';
        //echo $search_date;die();
        $gainApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->whereIn('gain_download', [0, 3])
            ->whereIn('law_no', [17103, 27103, 6111, 5510, 14857, 7252,3294])
            ->where('accrual', '>=', '2020-01-01')
            ->get();
        foreach ($gainApprovedIncentive as $gain_value) {
            $gainFileName = $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $gain_value->document_no . DIRECTORY_SEPARATOR . $gain_value->document_no . "_gain.pdf";
            $gainFileStatus = 0;
            if ($gain_value->gain_download == 0) {
                $fileControl = Storage::disk('pdfs')->exists($gainFileName);
                if ($fileControl) {
                    $size = Storage::disk('pdfs')->size($gainFileName);
                    if ($size >= 20) {
                        $gainFileStatus = 0;
                    }
                }
            } else {
                $gainFileStatus = 0;
            }
            if ($gainFileStatus == 0) {
                $gainLimit++;
                if ($gainLimit > $timeOutGainLimit) {
                    $progress = session()->get('progress') + 1;
                    session(["progress" => $progress]);
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => 'Tahakkuk listeleri indiriliyor.',
                            'progress' => $progress,
                            'step' => 'v2_pdf_download',
                        ]);
                }
                $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/pdfGosterim.action')
                    ->withData([
                        'struts.token.name' => 'token',
                        'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                        'tip' => 'tahakkukonayliFisTahakkukPdf',
                        'download' => 'false',
                        'hizmet_yil_ay_index' => session()->get('lastMounth'),
                        'hizmet_yil_ay_index_bitis' => '1',
                        'bildirgeRefNo' => $gain_value->document_no
                    ])
                    ->allowRedirect()
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->post();
                Storage::disk("pdfs")->put($gainFileName, $response);
                $gainFileStatus = 1;
            }
            // Save Status
            $change = ApprovedIncentive::find($gain_value->id);
            $change->gain_download = $gainFileStatus;
            $change->save();
        }
        if ($ApprovedIncentive->count() == 0 && $gainApprovedIncentive->count() == 0) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'Tahakkuk listeleri indirildi.',
                    'progress' => 30,
                    'step' => 'v2_pdf_parse',
                ]);
        } else {
            $progress = session()->get('progress') + 1;
            session(["progress" => $progress]);
            return response()
                ->json([
                    'code' => 'TIMEOUT',
                    'message' => 'Tahakkuk listeleri indiriliyor.',
                    'progress' => $progress,
                    'step' => 'v2_pdf_download',
                ]);
        }
    }

    public function v2PdfParse()
    {

        $sgk_company = getSgkCompany();
        $timeOutLimit = rand(3, 5);
        $limit = 0;
        $ApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->where("pdf_parse", 0)
            ->where('pdf_download', 1)
            ->get();

        foreach ($ApprovedIncentive as $value) {
            $limit++;
            if ($limit > $timeOutLimit) {
                $progress = session()->get('progress') + 1;
                session(["progress" => $progress]);
                return response()
                    ->json([
                        'code' => 'TIMEOUT',
                        'message' => 'Tahakkuk listeleri okunuyor.',
                        'progress' => $progress,
                        'step' => 'v2_pdf_parse',
                    ]);
            }


            $documentNo = $value->document_no;
            $readfile = storage_path('app' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $documentNo . DIRECTORY_SEPARATOR . $documentNo . '.pdf');

//            Windows
            // Config::set('pdftohtml.bin', 'C:\poppler\bin\pdftohtml.exe');
            // Config::set('pdfinfo.bin', 'C:\poppler\bin\pdftohtml.exe');

            $pdf = new Pdf($readfile);
            if (isset($pdf->getInfo()['creator'])) {
                $html = $pdf->html();
                for ($i = 1; $i <= $pdf->getPages(); $i++) {
                    $pdf = new Pdf($readfile);
                    $html = $pdf->html();
                    $html = $html->goToPage($i);
                    preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);
                    $personels = [];
                    foreach ($html[1] as $html1_key => $html_1) {
                        $personels[$html_1][] = $html[2][$html1_key];
                    }
                    foreach ($personels as $key => $personel) {
                        if (count($personel) > 10) {
                            $plus = 0;

                            if (isset($personel[12]) and strstr($personel[12], ".")) {
                                $filterCount = 14;
                            } elseif (isset($personel[13]) and strstr($personel[13], ".")) {
                                $filterCount = 14;
                            } else {
                                $filterCount = 13;
                            }
                            if (count($personel) == $filterCount) {
                                $plus = 1;
                            }
                            $control = IncentiveService::where("approved_incentive_id", $value->id)->select('id')
                                ->where("tck", $personel[1])
                                ->where("meslek_kod", isset($personel[12 + $plus]) ? $personel[12 + $plus] : 0)
                                ->get()->count();
                            if ($control == 0) {

                                $year_array = explode('-', $value->accrual);
                                if (isset($year_array[0])) {
                                    $year = $year_array[0];
                                } else {
                                    echo 'date_error';
                                    die();
                                }
                                if (isset($personel[8 + $plus]) and trim($personel[8 + $plus]) != 0) {
                                    if (strlen($personel[8 + $plus]) == 4) {
                                        $job_start = $year . "-" . str_split($personel[8 + $plus])[2] . str_split($personel[8 + $plus])[3] . "-" . str_split($personel[8 + $plus])[0] . str_split($personel[8 + $plus])[1];
                                    } elseif (strlen($personel[8 + $plus]) == 3) {
                                        $job_start = $year . "-" . str_split($personel[8 + $plus])[1] . str_split($personel[8 + $plus])[2] . "-0" . str_split($personel[8 + $plus])[0];
                                    } else {
                                        $job_start = null;
                                    }
                                } else {
                                    $job_start = null;
                                }
                                if (isset($personel[9 + $plus]) and trim($personel[9 + $plus]) != 0) {
                                    if (strlen($personel[9 + $plus]) == 4) {
                                        $job_finish = $year . "-" . str_split($personel[9 + $plus])[2] . str_split($personel[9 + $plus])[3] . "-" . str_split($personel[9 + $plus])[0] . str_split($personel[9 + $plus])[1];
                                    } elseif (strlen($personel[9 + $plus]) == 3) {
                                        $job_finish = $year . "-" . str_split($personel[9 + $plus])[1] . str_split($personel[9 + $plus])[2] . "-0" . str_split($personel[9 + $plus])[0];
                                    } else {
                                        $job_finish = null;
                                    }
                                } else {
                                    $job_finish = null;
                                }

                                $save = new IncentiveService();
                                $save->approved_incentive_id = $value->id;
                                $save->sgk_company_id = $value->sgk_company_id;
                                $save->accrual = $value->accrual;
                                $save->tck = $personel[1];
                                $save->isim = $personel[2];
                                $save->soyisim = $personel[3];
                                if (count($personel) == 14) {
                                    $save->kizlik_soyadi = $personel[4];
                                }
                                $save->ucret_tl = decimalFormatMysql($personel[4 + $plus]);
                                $save->ikramiye_tl = decimalFormatMysql($personel[5 + $plus]);
                                $save->gun = $personel[6 + $plus];
                                $save->eksik_gun = $personel[7 + $plus];
                                $save->job_start = $job_start;
                                $save->job_finish = $job_finish;
                                $save->icn = $personel[10 + $plus];
                                $save->egn = isset($personel[11 + $plus]) ? $personel[11 + $plus] : 0;
                                if (isset($personel[12 + $plus])) {
                                    $save->meslek_kod = $personel[12 + $plus];
                                } else {
                                }
                                $save->save();
                            }
                        }
                    }
                }
            }

            ApprovedIncentive::where('id', $value->id)->update([
                'pdf_parse' => 1,
            ]);
        }


        //KAZANÇ İÇİN
        $gain = [];
        $gainTimeOutLimit = rand(5, 10);
        $gainLimit = 0;
        $gainApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->where("gain_parse", 0)
            ->where('gain_download', 1)
            ->get();

        foreach ($gainApprovedIncentive as $gain_value) {
            $gainLimit++;
            if ($gainLimit > $gainTimeOutLimit) {
                $progress = session()->get('progress') + 1;
                session(["progress" => $progress]);
                return response()
                    ->json([
                        'code' => 'TIMEOUT',
                        'message' => 'Kazanç Tahakkuk listeleri okunuyor.',
                        'progress' => $progress,
                        'step' => 'v2_pdf_parse',
                    ]);
            }
            $documentNo = $gain_value->document_no;
            $readfile = storage_path('app' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $documentNo . DIRECTORY_SEPARATOR . $documentNo . '_gain.pdf');
            $pdf = new Pdf($readfile);
            $total = 0;
            $oran_tutar = [];
            $tutar_5510 = [];
            if (isset($pdf->getInfo()['creator'])) {
                for ($i = 1; $i <= $pdf->getPages(); $i++) {
                    $pdf = new Pdf($readfile);
                    $html = $pdf->html();
                    $html = $html->goToPage($i);
                    //<p style="position:absolute;top:
                    preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);

                    //SAYILI KANUNDAN

                    $gain_html = $html[2];

                    foreach ($gain_html as $key => $html_gain) {

                        if (strstr($html_gain, "İŞSİZLİK TUTARI")) {
                            //dd($gain_html);
                            if (isset($gain_html[$key + 8])) {
                                $issizlik_tutari = decimalFormatMysql($gain_html[$key + 8]);
                                $oran_tutar[] = 0 - $issizlik_tutari;
                            }
                        }
                        if (strstr($html_gain, "KISA VADELİ SİGORTA KOLLARI PRİMİ")) {
                            if (isset($gain_html[$key + 1])) {
                                $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                                $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                                $oran_tutar[] = (($toplam_pek * $oran_pek) / 100);
                            }
                        }
                        if (strstr($html_gain, "MALÜLLÜK YAŞLILIK VE ÖLÜM SİG. PRİMİ")) {
                            if (isset($gain_html[$key + 1])) {
                                $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                                $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                                $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                            }
                        }
                        if (strstr($html_gain, "GENEL SAĞLIK SİGORTASI PRİMİ")) {
                            if (isset($gain_html[$key + 1])) {
                                $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                                $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                                $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                            }
                        }

                        if (strstr($html_gain, "İŞSİZLİK SİGORTASI PRİMİ")) {
                            if (isset($gain_html[$key + 1])) {
                                $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                                $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                                $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                            }
                        }


                        if (strstr($html_gain, "SOSYAL GÜVENLİK DESTEK PRİMİ")) {
                            if (isset($gain_html[$key + 1])) {
                                $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                                $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                                $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                            }
                        }

                        if (strstr($html_gain, "5510 SAYILI KANUNDAN")) {
                            if (isset($gain_html[$key + 1])) {
                                $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                    ->where('approved_incentive_id', $gain_value->id)
                                    ->where('accrual', $gain_value->accrual)->first();
                                if ($has_gain) {
                                    $save = $has_gain;
                                } else {
                                    $save = new GainIncentive();
                                }
                                $save->sgk_company_id = $sgk_company->id;
                                $save->approved_incentive_id = $gain_value->id;
                                $save->accrual = $gain_value->accrual;
                                $save->law_5510 = $save->law_5510 + decimalFormatMysql($gain_html[$key + 1]);
                                $save->save();
                            }
                        }

                        if (strstr($html_gain, "7252 SAYILI KANUNDAN")) {
                            if (isset($gain_html[$key + 1])) {

                                $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                    ->where('approved_incentive_id', $gain_value->id)
                                    ->where('accrual', $gain_value->accrual)->first();
                                if ($has_gain) {
                                    $save = $has_gain;
                                } else {
                                    $save = new GainIncentive();
                                }
                                $save->sgk_company_id = $sgk_company->id;
                                $save->approved_incentive_id = $gain_value->id;
                                $save->accrual = $gain_value->accrual;
                                $amount = explode('<br />', $gain_html[$key + 1]);
                                if (isset($amount[0])) {
                                    $total = decimalFormatMysql($amount[0]);
                                }
                                $save->law_7252 = $save->law_7252 + $total;
                                $save->save();
                            }
                        }

                        if (strstr($html_gain, "4857 SAYILI KANUNDAN")) {
                            if (isset($gain_html[$key + 1])) {
                                $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                    ->where('approved_incentive_id', $gain_value->id)
                                    ->where('accrual', $gain_value->accrual)->first();
                                if ($has_gain) {
                                    $save = $has_gain;
                                } else {
                                    $save = new GainIncentive();
                                }
                                $save->sgk_company_id = $sgk_company->id;
                                $save->approved_incentive_id = $gain_value->id;
                                $save->accrual = $gain_value->accrual;
                                $save->law_14857 = $save->law_14857 + decimalFormatMysql($gain_html[$key + 1]);
                                $save->save();
                            }
                        }
                        if (strstr($html_gain, "6111 SAYILI KANUNDAN")) {
                            if (isset($gain_html[$key + 1])) {
                                $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                    ->where('approved_incentive_id', $gain_value->id)
                                    ->where('accrual', $gain_value->accrual)->first();
                                if ($has_gain) {
                                    $save = $has_gain;
                                } else {
                                    $save = new GainIncentive();
                                }
                                $save->sgk_company_id = $sgk_company->id;
                                $save->approved_incentive_id = $gain_value->id;
                                $save->accrual = $gain_value->accrual;
                                $save->law_6111 = $save->law_6111 + decimalFormatMysql($gain_html[$key + 1]);
                                $save->save();
                            }

                        } elseif (strstr($html_gain, "103 SAYILI KANUNDAN")) {
                            if (isset($gain_html[$key + 1])) {

                                $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                    ->where('approved_incentive_id', $gain_value->id)
                                    ->where('accrual', $gain_value->accrual)->first();
                                if ($has_gain) {
                                    $save = $has_gain;
                                } else {
                                    $save = new GainIncentive();
                                }
                                $save->sgk_company_id = $sgk_company->id;
                                $save->approved_incentive_id = $gain_value->id;
                                $save->accrual = $gain_value->accrual;
                                $amount = explode('<br />', $gain_html[$key + 1]);
                                if (isset($amount[0])) {
                                    $total = decimalFormatMysql($amount[0]);
                                }
                                $save->law_27103 = $save->law_27103 + $total;
                                $save->save();
                            }

                        }
                        elseif (strstr($html_gain, "3294")) {

                            if (isset($gain_html[60])) {

                                $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                    ->where('approved_incentive_id', $gain_value->id)
                                    ->where('accrual', $gain_value->accrual)->first();
                                if ($has_gain) {
                                    $save = $has_gain;
                                } else {
                                    $save = new GainIncentive();
                                }
                                $save->sgk_company_id = $sgk_company->id;
                                $save->approved_incentive_id = $gain_value->id;
                                $save->accrual = $gain_value->accrual;
                                $amount = explode('<br />', $gain_html[60]);

                                if (isset($amount[0])) {
                                    $total = decimalFormatMysql($amount[0]);
                                }

                                $save->law_3294 =  $total;
                                $save->save();
                            }

                        }
                    }


                }
            }


            $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                ->where('approved_incentive_id', $gain_value->id)
                ->where('accrual', $gain_value->accrual)->first();
            if ($has_gain) {
                $save = $has_gain;
                $save->total_amount = $save->total_amount + array_sum($oran_tutar);
                $save->save();
            }
            ApprovedIncentive::where('id', $gain_value->id)->update([
                'gain_parse' => 1,
            ]);
        }
        SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
        //KAZANÇ BİTTİ
        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => 'Tahakkuk listeleri okundu.',
                'progress' => 40,
                'step' => 'v3_new_request',
            ]);
    }

    public function v2PdfParseMetrik()
    {
        $sgk_company = getSgkCompany();
        $timeOutLimit = rand(5, 10);
        $limit = 0;
        $ApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->where("pdf_parse", 0)
            ->where('pdf_download', 1)
            ->get();
        foreach ($ApprovedIncentive as $value) {
            $limit++;
            if ($limit > $timeOutLimit) {
                $progress = session()->get('progress') + 1;
                session(["progress" => $progress]);
                return response()
                    ->json([
                        'code' => 'TIMEOUT',
                        'message' => 'Tahakkuk listeleri okunuyor.',
                        'progress' => $progress,
                        'step' => 'v2_pdf_parse_metrik',
                    ]);
            }
            $documentNo = $value->document_no;
            $readfile = storage_path('app' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $documentNo . DIRECTORY_SEPARATOR . $documentNo . '.pdf');
//            Windows
//            Config::set('pdftohtml.bin', 'C:\poppler\bin\pdftohtml.exe');
//            Config::set('pdfinfo.bin', 'C:\poppler\bin\pdftohtml.exe');

            $pdf = new Pdf($readfile);
            $html = $pdf->html();
            for ($i = 1; $i <= $pdf->getPages(); $i++) {
                $pdf = new Pdf($readfile);
                $html = $pdf->html();
                $html = $html->goToPage($i);
                preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);
                $personels = [];
                foreach ($html[1] as $html1_key => $html_1) {
                    $personels[$html_1][] = $html[2][$html1_key];
                }
                foreach ($personels as $key => $personel) {
                    if (count($personel) > 10) {
                        $plus = 0;

                        if (isset($personel[12]) and strstr($personel[12], ".")) {
                            $filterCount = 14;
                        } elseif (isset($personel[13]) and strstr($personel[13], ".")) {
                            $filterCount = 14;
                        } else {
                            $filterCount = 13;
                        }
                        if (count($personel) == $filterCount) {
                            $plus = 1;
                        }
                        $control = IncentiveService::where("approved_incentive_id", $value->id)->select('id')
                            ->where("tck", $personel[1])
                            ->get()->count();
                        if ($control == 0) {

                            $year_array = explode('-', $value->accrual);
                            if (isset($year_array[0])) {
                                $year = $year_array[0];
                            } else {
                                $year = date('Y');
                            }
                            if (isset($personel[8 + $plus]) and trim($personel[8 + $plus]) != 0) {
                                if (strlen($personel[8 + $plus]) == 4) {
                                    $job_start = $year . "-" . str_split($personel[8 + $plus])[2] . str_split($personel[8 + $plus])[3] . "-" . str_split($personel[8 + $plus])[0] . str_split($personel[8 + $plus])[1];
                                } elseif (strlen($personel[8 + $plus]) == 3) {
                                    $job_start = $year . "-" . str_split($personel[8 + $plus])[1] . str_split($personel[8 + $plus])[2] . "-0" . str_split($personel[8 + $plus])[0];
                                } else {
                                    $job_start = null;
                                }
                            } else {
                                $job_start = null;
                            }
                            if (isset($personel[9 + $plus]) and trim($personel[9 + $plus]) != 0) {
                                if (strlen($personel[9 + $plus]) == 4) {
                                    $job_finish = $year . "-" . str_split($personel[9 + $plus])[2] . str_split($personel[9 + $plus])[3] . "-" . str_split($personel[9 + $plus])[0] . str_split($personel[9 + $plus])[1];
                                } elseif (strlen($personel[9 + $plus]) == 3) {
                                    $job_finish = $year . "-" . str_split($personel[9 + $plus])[1] . str_split($personel[9 + $plus])[2] . "-0" . str_split($personel[9 + $plus])[0];
                                } else {
                                    $job_finish = null;
                                }
                            } else {
                                $job_finish = null;
                            }

                            $save = new IncentiveService();
                            $save->approved_incentive_id = $value->id;
                            $save->sgk_company_id = $value->sgk_company_id;
                            $save->accrual = $value->accrual;
                            $save->tck = $personel[1];
                            $save->isim = $personel[2];
                            $save->soyisim = $personel[3];
                            if (count($personel) == 14) {
                                $save->kizlik_soyadi = $personel[4];
                            }
                            $save->ucret_tl = decimalFormatMysql($personel[4 + $plus]);
                            $save->ikramiye_tl = decimalFormatMysql($personel[5 + $plus]);
                            $save->gun = $personel[6 + $plus];
                            $save->eksik_gun = $personel[7 + $plus];
                            $save->job_start = $job_start;
                            $save->job_finish = $job_finish;
                            $save->icn = $personel[10 + $plus];
                            $save->egn = isset($personel[11 + $plus]) ? $personel[11 + $plus] : 0;
                            if (isset($personel[12 + $plus])) {
                                $save->meslek_kod = $personel[12 + $plus];
                            } else {
                            }
                            $save->save();
                        }
                    }
                }
            }
            ApprovedIncentive::where('id', $value->id)->update([
                'pdf_parse' => 1,
            ]);
        }


        //KAZANÇ İÇİN
        $gain = [];
        $gainTimeOutLimit = rand(5, 10);
        $gainLimit = 0;
        $gainApprovedIncentive = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->where("gain_parse", 0)
            ->where('gain_download', 1)
            ->get();

        foreach ($gainApprovedIncentive as $gain_value) {
            $gainLimit++;
            if ($gainLimit > $gainTimeOutLimit) {
                $progress = session()->get('progress') + 1;
                session(["progress" => $progress]);
                return response()
                    ->json([
                        'code' => 'TIMEOUT',
                        'message' => 'Kazanç Tahakkuk listeleri okunuyor.',
                        'progress' => $progress,
                        'step' => 'v2_pdf_parse_metrik',
                    ]);
            }
            $documentNo = $gain_value->document_no;
            $readfile = storage_path('app' . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR . $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $documentNo . DIRECTORY_SEPARATOR . $documentNo . '_gain.pdf');
///home/ikmetrik/public_html/tesvik2/storage/app/pdfs/18662255690-10/111407-2018-2/111407-2018-2_gain.pdf"
            $pdf = new Pdf($readfile);
            //dd($readfile);
            $total = 0;
            $oran_tutar = [];
            $tutar_5510 = [];
            for ($i = 1; $i <= $pdf->getPages(); $i++) {
                $pdf = new Pdf($readfile);
                $html = $pdf->html();
                $html = $html->goToPage($i);
                //<p style="position:absolute;top:
                preg_match_all('/<p style=".*?top:(.*?)px;.*?" class=".*?">(.*?)<\/p>/', $html, $html);

                //SAYILI KANUNDAN

                $gain_html = $html[2];
                foreach ($gain_html as $key => $html_gain) {
                    if (strstr($html_gain, "İŞSİZLİK TUTARI")) {
                        //dd($gain_html);
                        if (isset($gain_html[$key + 8])) {
                            $issizlik_tutari = decimalFormatMysql($gain_html[$key + 8]);
                            $oran_tutar[] = 0 - $issizlik_tutari;
                        }
                    }
                    if (strstr($html_gain, "KISA VADELİ SİGORTA KOLLARI PRİMİ")) {
                        if (isset($gain_html[$key + 1])) {
                            $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                            $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                            $oran_tutar[] = (($toplam_pek * $oran_pek) / 100);
                        }
                    }
                    if (strstr($html_gain, "MALÜLLÜK YAŞLILIK VE ÖLÜM SİG. PRİMİ")) {
                        if (isset($gain_html[$key + 1])) {
                            $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                            $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                            $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                        }
                    }
                    if (strstr($html_gain, "GENEL SAĞLIK SİGORTASI PRİMİ")) {
                        if (isset($gain_html[$key + 1])) {
                            $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                            $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                            $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                        }
                    }

                    if (strstr($html_gain, "İŞSİZLİK SİGORTASI PRİMİ")) {
                        if (isset($gain_html[$key + 1])) {
                            $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                            $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                            $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                        }
                    }


                    if (strstr($html_gain, "SOSYAL GÜVENLİK DESTEK PRİMİ")) {
                        if (isset($gain_html[$key + 1])) {
                            $toplam_pek = decimalFormatMysql($gain_html[$key + 1]);
                            $oran_pek = decimalFormatMysql($gain_html[$key + 2]);
                            $oran_tutar[] = ($toplam_pek * $oran_pek) / 100;
                        }
                    }

                    if (strstr($html_gain, "5510 SAYILI KANUNDAN")) {
                        if (isset($gain_html[$key + 1])) {
                            $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                ->where('approved_incentive_id', $gain_value->id)
                                ->where('accrual', $gain_value->accrual)->first();
                            if ($has_gain) {
                                $save = $has_gain;
                            } else {
                                $save = new GainIncentive();
                            }
                            $save->sgk_company_id = $sgk_company->id;
                            $save->approved_incentive_id = $gain_value->id;
                            $save->accrual = $gain_value->accrual;
                            $save->law_5510 = $save->law_5510 + decimalFormatMysql($gain_html[$key + 1]);
                            $save->save();
                        }
                    }
                    if (strstr($html_gain, "7252 SAYILI KANUNDAN")) {
                        if (isset($gain_html[$key + 1])) {

                            $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                ->where('approved_incentive_id', $gain_value->id)
                                ->where('accrual', $gain_value->accrual)->first();
                            if ($has_gain) {
                                $save = $has_gain;
                            } else {
                                $save = new GainIncentive();
                            }
                            $save->sgk_company_id = $sgk_company->id;
                            $save->approved_incentive_id = $gain_value->id;
                            $save->accrual = $gain_value->accrual;
                            $amount = explode('<br />', $gain_html[$key + 1]);
                            if (isset($amount[0])) {
                                $total = decimalFormatMysql($amount[0]);
                            }
                            $save->law_7252 = $save->law_7252 + $total;
                            $save->save();
                        }
                    }
                    if (strstr($html_gain, "4857 SAYILI KANUNDAN")) {
                        if (isset($gain_html[$key + 1])) {
                            $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                ->where('approved_incentive_id', $gain_value->id)
                                ->where('accrual', $gain_value->accrual)->first();
                            if ($has_gain) {
                                $save = $has_gain;
                            } else {
                                $save = new GainIncentive();
                            }
                            $save->sgk_company_id = $sgk_company->id;
                            $save->approved_incentive_id = $gain_value->id;
                            $save->accrual = $gain_value->accrual;
                            $save->law_14857 = $save->law_14857 + decimalFormatMysql($gain_html[$key + 1]);
                            $save->save();
                        }
                    }
                    if (strstr($html_gain, "6111 SAYILI KANUNDAN")) {
                        if (isset($gain_html[$key + 1])) {
                            $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                ->where('approved_incentive_id', $gain_value->id)
                                ->where('accrual', $gain_value->accrual)->first();
                            if ($has_gain) {
                                $save = $has_gain;
                            } else {
                                $save = new GainIncentive();
                            }
                            $save->sgk_company_id = $sgk_company->id;
                            $save->approved_incentive_id = $gain_value->id;
                            $save->accrual = $gain_value->accrual;
                            $save->law_6111 = $save->law_6111 + decimalFormatMysql($gain_html[$key + 1]);
                            $save->save();
                        }

                    } elseif (strstr($html_gain, "103 SAYILI KANUNDAN")) {
                        if (isset($gain_html[$key + 1])) {

                            $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                                ->where('approved_incentive_id', $gain_value->id)
                                ->where('accrual', $gain_value->accrual)->first();
                            if ($has_gain) {
                                $save = $has_gain;
                            } else {
                                $save = new GainIncentive();
                            }
                            $save->sgk_company_id = $sgk_company->id;
                            $save->approved_incentive_id = $gain_value->id;
                            $save->accrual = $gain_value->accrual;
                            $amount = explode('<br />', $gain_html[$key + 1]);
                            if (isset($amount[0])) {
                                $total = decimalFormatMysql($amount[0]);
                            }
                            $save->law_27103 = $save->law_27103 + $total;
                            $save->save();
                        }
                    }
                }


            }

            $has_gain = GainIncentive::where('sgk_company_id', $sgk_company->id)
                ->where('approved_incentive_id', $gain_value->id)
                ->where('accrual', $gain_value->accrual)->first();
            if ($has_gain) {
                $save = $has_gain;
                $save->total_amount = $save->total_amount + array_sum($oran_tutar);
                $save->save();
            }
            ApprovedIncentive::where('id', $gain_value->id)->update([
                'gain_parse' => 1,
            ]);
        }
        SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
        //KAZANÇ BİTTİ
        return response()
            ->json([
                'code' => 'FINISH',
                'message' => 'Gerekli veriler çekildi.',
                'progress' => 100,
                'url' => route('declarations.incentives.metrik')
            ]);

    }

    public function v3NewRequest()
    {
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);

        $loginProcedure = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikTanimlama.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        preg_match('/isverenToken=(.*?)"/', $loginProcedure, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_new_request'
                ]);
        }

        Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvikTanimlama.action;')
            ->withData([
                'isverenToken' => $tokken[1],
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        $declaration1 = Declaration::where("sgk_company_id", $sgk_company->id)->orderBy('declarations_date', 'DESC')->first();


        if ($declaration1) {
            $errorss = [];
            $declarations = Declaration::where("sgk_company_id", $sgk_company->id)
                ->where('declarations_date', $declaration1->declarations_date)
                ->where('document_type','!=',02)
                ->pluck('id');
            $declaration_services = DeclarationService::whereIn('declaration_id', $declarations)->where('request', 0)->get();

            $progressRequest = 0;



            foreach ($declaration_services as $declaration_service) {

                $progress = session()->get('progress') + 1;
                session(["progress" => $progress]);
                $progressRequest++;
                if ($progressRequest == 10) {
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => 'Hakedişler sorgulanıyor.',
                            'progress' => $progress,
                            'step' => 'v3_new_request',
                        ]);
                }
                $istek = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvikTanimlama.action')
                    ->withHeader('Referer: https://uyg.sgk.gov.tr/YeniSistem')
                    ->withData([
                        'iseGirisSirali' => false,
                        'tcDonemSorgu' => 0,
                        'kolayIsveren' => false,
                        'donem_yil_ay_index' => 0,
                        'tcKimlikNo' => trim($declaration_service->tck),
                        'action:uygunSigortaliBilgileriKimlik' => 'Sorgula'
                    ])
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->post();


                preg_match_all('/<center id=\'genelUyariCenterTag\' style=\'.*\' >(.*?)<\/center>/', $istek, $error);
                if (isset($error[1][0]))
                {
                    $errorss[$sgk_company->id][$declaration_service->tck] = array('0'=>$error[1][0],'1' => $declaration_service->isim.' '.$declaration_service->soyisim);

                }
                session(['errorss' => $errorss]);

                $istek = str_replace("\t", "", $istek);
                $istek = str_replace("\r", "", $istek);
                $istek = str_replace("\n", "", $istek);
                if (strstr($istek, "Hata Kod : x301")) {
                    $cookieKey = startCookieV3($sgk_company);
                    return response()
                        ->json([
                            'code' => 'LOGIN_FAIL',
                            'key' => $cookieKey,
                            'message' => 'İşveren Sistemi Giriş Hatası.'
                        ]);
                }
                preg_match('/id="uygunSigortaliBilgileriKimlik_iseGirisMapIndex.*".value="(.*)".title="İşlem Yapmak İçin İşaretleyiniz"/', $istek, $istekValue);

                $map_index_array = [];

                $istek = explode('İşe Giriş Bildirgesi Evrak Tarihi</p></tr>   <tr>', $istek);
                if (isset($istek[1])) {
                    preg_match('/<p>(.*)<\/p>/', $istek[1], $istekValue);

                    $istek1 = explode('" title="İşlem Yapmak İçin İşaretleyiniz"', $istekValue[1]);

                    foreach ($istek1 as $key => $istek) {
                        preg_match('/id="uygunSigortaliBilgileriKimlik_iseGirisMapIndex.*" value="(.*)/', $istek, $istek2);
                        if (isset($istek2[1])) {
                            $map_index_array[] = $istek2[1];
                        }
                    }
                }
                $d = 1;
                foreach ($map_index_array as $key => $map_index) {
                    if ($d == 1) {
                        $dates = explode('.', $map_index);
                        $start_date = $dates[2];
                    }
                    $d++;
                }

                if (isset($start_date)) {
                    foreach ($map_index_array as $key => $map_index) {
                        $dates = explode('.', $map_index);
                        $last_map = $dates[0] . '.' . $dates[1] . '.' . $start_date;
                        if ($map_index == $last_map) {
                            $last_map_index = $map_index;
                        }
                    }

                    if (isset($last_map_index)) {


                        $tesvikSorgulama = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/uygunSigortaliBilgileriKimlik.action')
                            ->withHeader('Referer: https://uyg.sgk.gov.tr/YeniSistem')
                            ->withData([
                                'iseGirisSirali' => false,
                                'tcDonemSorgu' => 2,
                                'kolayIsveren' => false,
                                'donem_yil_ay_index' => 0,
                                'tcKimlikNo' => trim($declaration_service->tck),
                                'egitimDurumu' => 1,
                                'kazanc' => '3577,50',
                                'ucretDestegiTalebiVarMi' => true,
                                'iseGirisMapIndex' => $last_map_index,
                                'action:sigortaliSorgula' => 'Devam'
                            ])
                            ->allowRedirect()
                            ->setCookieJar($cookieFile)
                            ->setCookieFile($cookieFile)
                            ->post();


                           // hata mesajı gelecek sistem durdurulacak burada

                        $tarih = explode('.', $last_map_index);
                        if (count($tarih) == 3) {
                            $ta = str_replace('-', '.', $tarih[2]);
                        }
                        $tesvikSorgulama = botPageHtmlCleaner($tesvikSorgulama, ['#', ' TL', '  ']);
                           preg_match('/<p.*align="center">(.*)<\/p><\/td><\/tr><form.*id="tesvikTanimlamaListeyeDon"/', $tesvikSorgulama, $job_start);
                          preg_match_all('/<center id=\'genelUyariCenterTag\' style=\'color:blue; margin: 5px; font-size: 17px;\' >(.*)<\/center>/', $tesvikSorgulama, $error);
                            $errorss[$sgk_company->id][$declaration_service->tck] = array('0'=>$error[1],'1' => $declaration_service->isim.' '.$declaration_service->soyisim);
                           session(['errorss' => $errorss]);
                        if (isset($job_start[1])) {
                            $jobStart = trim($job_start[1]);
                        } else {
                            if (!empty($declaration_service->job_start)) {
                                $jobStart = $declaration_service->job_start;
                            } else {
                                $jobStart = null;
                            }
                        }

                        preg_match_all('#<table id="dataTable".*?>(.*?)<\/table>#', $tesvikSorgulama, $table);

                        //işKur Kaydı OLmadığı İçin Faydalanamayanlar

                       // session(['iskur_errors'=>$iskur_errors]);
                        //Bitiş

                        foreach ($table[1] as $key =>  $tab) {

                            $a = Declaration::where("sgk_company_id", $sgk_company->id)
                                ->where('declarations_date', $declaration1->declarations_date)
                                ->pluck('id');
                            $control = DeclarationService::whereIn('declaration_id', $a)->whereNotNull('job_start')->where('tck',$declaration_service->tck)->count();
                            preg_match_all('#<p>(.*?)<\/p>#', $tab, $mesaj);
                            if(strstr($mesaj[1][0],"27103 /4447 SAYILI KANUN GEÇİCİ 19.MADDE") or strstr($mesaj[1][0],"17103 /4447 SAYILI KANUN GEÇİCİ 19.MADDE"))
                            {
                                if (strstr($mesaj[1][4],"Sigortalı İş-Kur'a kayıtlı değil. Teşvikten faydalanılamaz."))
                                {
                                    if (!isset($iskur_errors[$declaration_service->tck]) and $control >0 )
                                    {
                                        $iskur_errors[$declaration_service->tck][] = ['tck'=>$declaration_service->tck,'first_name'=>$declaration_service->isim,'last_name'=>$declaration_service->soyisim,'law'=>$mesaj[1][0],'mesaj'=>$mesaj[1][4]];
                                        session(['iskur_errors'=>$iskur_errors]);
                                    }

                                }

                            }


                            if (strstr($tab, "b3ffd9")) {
                                preg_match('#<p>(.*?)<\/p>#', $tab, $p);

                                $a = str_replace(' ', '', $p[1]);
                                $k = explode("/", $a);
                                $laws[] = $k[0];

                            }

                        }

                        if ($jobStart) {
                            preg_match_all('#<table id="dataTable".*?>(.*?)<\/table>#', $tesvikSorgulama, $table);

                            foreach ($table[1] as $tab) {
                                if (strstr($tab, "b3ffd9")) {
                                    preg_match('#<p>(.*?)<\/p>#', $tab, $p);

                                    $a = str_replace(' ', '', $p[1]);
                                    $k = explode("/", $a);
                                    $laws[] = $k[0];

                                }
                            }
                        }


                        if (isset($laws)) {
                            $laws_taranacak = session()->get('options_laws');
                            foreach ($laws_taranacak as $law) {
                                if (in_array($law, $laws)) {
                                    $tesvik_tanimlama = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/sigortaliKaydet.action')
                                        ->withHeader('Referer: https://uyg.sgk.gov.tr/YeniSistem')
                                        ->withData([
                                            'donem_yil_ay_index' => 0,
                                            'tcDonemSorgu' => 2,
                                            'tcKimlikNo' => $declaration_service->tck,
                                            'iseGirisTarihi' => isset($ta) ? $ta : $jobStart, // gun.ay.yil
                                            'egitimDurumu' => 1,
                                            'meslekKodu' => "4323.52",
                                            'kanunNo' => $law,
                                            'kazanc' => '3577,50',
                                            'kolayIsveren' => false,
                                            'ucretDestegiTalebiVarMi' => false,
                                        ])
                                        ->allowRedirect()
                                        ->setCookieJar($cookieFile)
                                        ->setCookieFile($cookieFile)
                                        ->post();
                                }

                            }
                        }
                    }
                }
                $declaration_services2 = DeclarationService::where('declaration_id', $declaration_service->declaration_id)->where('tck', $declaration_service->tck)->first();
                $declaration_services2->request = 1;
                $declaration_services2->save();


            }
        }




        /*
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => 'Yeni girişler ve 30.Madde Girişleri  bildirildi!',
                        'progress' => 80,
                        'step' => 'v3_6111',
                    ]);
        */

        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => 'Yeni girişler bildirildii!',
                'progress' => 80,
                'step' => 'v3_3294',
            ]);

    }

    public function v3OldEncouragementSave_3294()
    {
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);
        $returnStatus = false;
        $tokkenData = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikTanimlama.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();

        preg_match('/isverenToken=(.*?)"/', $tokkenData, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_3294'
                ]);
        }
        $tokken[1] = str_replace('"', '', $tokken[1]);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_3294'
                ]);
        }
        $tokken[1] = str_replace('"', '', $tokken[1]);

        $newIncentiveControl = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik3294_sigortali.action;')
            ->withData([
                'isverenToken' => $tokken[1],
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();


        if (strstr($newIncentiveControl, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'İşveren Sistemi 3294 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                    'progress' => 90,
                    'step' => 'v3_6111',
                ]);
        }

        $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();
        if (strstr($page_before, "[Hata Kod : x301]")) {
            $cookieKey = startCookieV3($sgk_company);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_3294'
                ]);
        }
        $fileName = $sgk_company->id . "-" . '3294.xlsx';

        if (Storage::disk('excel_incentives')->exists($fileName)) {
            Storage::disk('excel_incentives')->delete($fileName);
        }
        Storage::disk('excel_incentives')->put($fileName, $page_before);
        sleep(3);
        $excel = \SimpleXLSX::parse(Storage::disk('excel_incentives')->path($fileName));

        $xls = $excel->rows();


        unset($xls[0]);
        unset($xls[1]);
        $array_count = count($xls[2]);

        if ($array_count == 11) {
            foreach ($xls as $incentive) {
                $start = trim($incentive[5]) . "/01";
                $start = str_replace(" ", "", $start);
                $start = str_replace('/', '-', $start);
                $start = date("Y-m-d", strtotime($start));
                $finish = trim($incentive[6]) . "/01";
                $finish = str_replace(" ", "", $finish);
                $finish = str_replace('/', '-', $finish);
                $finish = date("Y-m-d", strtotime($finish));
                $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                $finish = $lastDayofMonth;

                // JOB DATE
                $job_start = date("Y-m-d", strtotime(trim($incentive[8])));
                if (strstr($incentive[9], '.')) {
                    $job_finish = date("Y-m-d", strtotime(trim($incentive[9])));
                } else {
                    $job_finish = "2025-02-25";
                }

                $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', 7316)->where('start', $start)->where('finish', $finish)->where('sgk_company_id',$sgk_company['id'])->get()->count();
                if ($saveControl == 0) {
                    $returnStatus = true;
                    $save = new Incentive();
                    $save->sgk_company_id = session()->get('selectedCompany')['id'];
                    $save->tck = $incentive[1];
                    $save->start = $start;
                    $save->finish = $finish;
                    $save->job_start = $job_start;
                    $save->job_finish = $job_finish;
                    $save->filter_status = 1;
                    $save->law = 3294;
                    $save->days = 9999;
                    if ($incentive[7] == "-") {
                        $save->min_personel = 0;
                    } else {
                        $min_personel = str_replace('.', '', trim($incentive[7]));
                        $min_personel = str_replace(',', '', $min_personel);
                        $save->min_personel = $min_personel;
                    }
                    $save->save();
                }
            }

            if ($returnStatus) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => '3294 İşveren Sistemi Listesi Kaydedildi',
                        'progress' => 90,
                        'step' => 'v3_6111',
                    ]);
            } else {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => 'İşveren Sistemi 3294 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                        'progress' => 90,
                        'step' => 'v3_6111',
                    ]);
            }
        }


    }

    public function v3OldEncouragementSave_6111()
    {

        if (session()->has('options_laws')) {
            $laws = session()->get('options_laws');
            if (!in_array('6111', $laws)) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => '6111 İşveren Sistemi Listesi Atlandı',
                        'progress' => 90,
                        'step' => 'v3_26',
                    ]);
            }
        }

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);
        $returnStatus = false;
        $tokkenData = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikTanimlama.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        preg_match('/isverenToken=(.*?)"/', $tokkenData, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_6111'
                ]);
        }
        $tokken[1] = str_replace('"', '', $tokken[1]);

        $newIncentiveControl = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik4447_10.action;')
            ->withData([
                'isverenToken' => $tokken[1],
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();

        if (strstr($newIncentiveControl, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'İşveren Sistemi 6111 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                    'progress' => 90,
                    'step' => 'v3_26',
                ]);
        }

        $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();
        if (strstr($page_before, "[Hata Kod : x301]")) {
            $cookieKey = startCookieV3($sgk_company);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_6111'
                ]);
        }
        $fileName = $sgk_company->id . "-" . '6111.xlsx';

        if (Storage::disk('excel_incentives')->exists($fileName)) {
            Storage::disk('excel_incentives')->delete($fileName);
        }
        Storage::disk('excel_incentives')->put($fileName, $page_before);
        sleep(3);
        $excel = \SimpleXLSX::parse(Storage::disk('excel_incentives')->path($fileName));
        $xls = $excel->rows();


        unset($xls[0]);
        unset($xls[1]);
        $array_count = count($xls[2]);

        if ($array_count == 12) {
            foreach ($xls as $incentive) {
                $start = trim($incentive[5]) . "/01";
                $start = str_replace(" ", "", $start);
                $start = str_replace('/', '-', $start);
                $start = date("Y-m-d", strtotime($start));
                $finish = trim($incentive[6]) . "/01";
                $finish = str_replace(" ", "", $finish);
                $finish = str_replace('/', '-', $finish);
                $finish = date("Y-m-d", strtotime($finish));
                $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                $finish = $lastDayofMonth;

                // JOB DATE
                $job_start = date("Y-m-d", strtotime(trim($incentive[8])));
                if (strstr($incentive[9], '.')) {
                    $job_finish = date("Y-m-d", strtotime(trim($incentive[9])));
                } else {
                    $job_finish = "2025-02-25";
                }

                $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', 6111)->where('start', $start)->where('finish', $finish)->where('sgk_company_id',$sgk_company['id'])->get()->count();
                if ($saveControl == 0) {
                    $returnStatus = true;
                    $save = new Incentive();
                    $save->sgk_company_id = session()->get('selectedCompany')['id'];
                    $save->tck = $incentive[1];
                    $save->start = $start;
                    $save->finish = $finish;
                    $save->job_start = $job_start;
                    $save->job_finish = $job_finish;
                    $save->filter_status = 1;
                    $save->law = 6111;
                    $save->days = 9999;
                    if ($incentive[7] == "-") {
                        $save->min_personel = 0;
                    } else {
                        $min_personel = str_replace('.', '', trim($incentive[7]));
                        $min_personel = str_replace(',', '', $min_personel);
                        $save->min_personel = $min_personel;
                    }
                    $save->save();
                }
            }

            if ($returnStatus) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => '6111 İşveren Sistemi Listesi Kaydedildi',
                        'progress' => 90,
                        'step' => 'v3_26',
                    ]);
            } else {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => 'İşveren Sistemi 6111 için Yeni Kişi Bulunamamıştır.',
                        'progress' => 90,
                        'step' => 'v3_26',
                    ]);
            }
        } else {


            foreach ($xls as $incentive) {
                $start = trim($incentive[5]) . "/01";
                $start = str_replace(" ", "", $start);
                $start = str_replace('/', '-', $start);
                $start = date("Y-m-d", strtotime($start));
                $finish = trim($incentive[6]) . "/01";
                $finish = str_replace(" ", "", $finish);
                $finish = str_replace('/', '-', $finish);
                $finish = date("Y-m-d", strtotime($finish));
                $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                $finish = $lastDayofMonth;

                // JOB DATE
                $job_start = date("Y-m-d", strtotime(trim($incentive[8])));
                if (strstr($incentive[9], '.')) {
                    $job_finish = date("Y-m-d", strtotime(trim($incentive[9])));
                } else {
                    $job_finish = "2025-02-25";
                }

                $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', 6111)->where('start', $start)->where('finish', $finish)->where('sgk_company_id',$sgk_company['id'])->where('finish', $finish)->get()->count();
                if ($saveControl == 0) {
                    $returnStatus = true;
                    $save = new Incentive();
                    $save->sgk_company_id = session()->get('selectedCompany')['id'];
                    $save->tck = $incentive[1];
                    $save->start = $start;
                    $save->finish = $finish;
                    $save->job_start = $job_start;
                    $save->job_finish = $job_finish;
                    $save->filter_status = 1;
                    $save->law = 6111;
                    $save->days = 9999;
                    if ($incentive[7] == "-") {
                        $save->min_personel = 0;
                    } else {
                        $min_personel = str_replace('.', '', trim($incentive[7]));
                        $min_personel = str_replace(',', '', $min_personel);
                        $save->min_personel = $min_personel;
                    }
                    $save->save();
                }
            }

            if ($returnStatus) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => '6111 İşveren Sistemi Listesi Kaydedildi',
                        'progress' => 90,
                        'step' => 'v3_26',
                    ]);
            } else {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => 'İşveren Sistemi 6111 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                        'progress' => 90,
                        'step' => 'v3_26',
                    ]);
            }
        }
    }

    public function v3OldEncouragementSave_26()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);

        $tokkenData = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikTanimlama.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();

        preg_match('/isverenToken=(.*?)"/', $tokkenData, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_26'
                ]);
        }
        if (session()->has('options_laws')) {
            $laws = session()->get('options_laws');
            if (in_array('7252', $laws)) {
                $newIncentiveControl = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik4447_26_sigortali.action;')
                    ->withData([
                        'isverenToken' => $tokken[1],
                    ])
                    ->allowRedirect()
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->get();
                sleep(3);
                if (strstr($newIncentiveControl, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) {
                    // Teşvik yok.
                } else {
                    $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
                        ->allowRedirect()
                        ->setCookieJar($cookieFile)
                        ->setCookieFile($cookieFile)
                        ->post();
                    if (strstr($page_before, "[Hata Kod : x301]")) {
                        $cookieKey = startCookieV3($sgk_company);
                        return response()
                            ->json([
                                'code' => 'LOGIN_FAIL',
                                'message' => 'İşveren Sistemi Giriş Hatası.',
                                'step' => 'v3_26'
                            ]);
                    }
                    $fileName = $sgk_company->id . "-" . '7252.xlsx';
                    Storage::disk('excel_incentives')->put($fileName, $page_before);
                    sleep(3);
                    $excel = \SimpleXLSX::parse(Storage::disk('excel_incentives')->path($fileName));

                    $xls = $excel->rows();

                    unset($xls[0]);
                    unset($xls[1]);
                    if (count($xls) == 0) {
                        // VERİ YOK
                    } else {
                        foreach ($xls as $incentive) {
                            $start = trim($incentive[5]) . "/01";
                            $start = str_replace(" ", "", $start);
                            $start = str_replace('/', '-', $start);
                            $start = date("Y-m-d", strtotime($start));
                            $finish = trim($incentive[6]) . "/01";
                            $finish = str_replace(" ", "", $finish);
                            $finish = str_replace('/', '-', $finish);
                            $finish = date("Y-m-d", strtotime($finish));
                            $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                            $finish = $lastDayofMonth;

                            // JOB DATE

                            $job_start = date("Y-m-d", strtotime(trim($incentive[10])));
                            if (strstr($incentive[11], '.')) {
                                $job_finish = date("Y-m-d", strtotime(trim($incentive[11])));
                            } else {
                                $job_finish = "2025-02-25";
                            }
                            $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', trim($incentive[9]))->where('sgk_company_id',$sgk_company['id'])->where('start', $start)->where('finish', $finish)->get()->count();
                            if ($saveControl == 0) {
                                $save = new Incentive();
                                $save->sgk_company_id = session()->get('selectedCompany')['id'];
                                $save->tck = trim($incentive[1]);
                                $save->law = trim($incentive[9]);
                                $save->start = $start;
                                $save->finish = $finish;
                                $save->min_personel = 0;
                                $save->days = trim($incentive[8]);
                                $save->job_start = $job_start;
                                $save->job_finish = $job_finish;
                                $save->save();
                            }
                        }
                    }
                }

            }
        }
        /*
                if (session()->has('options_laws')) {
                    $laws = session()->get('options_laws');
                    if (in_array('27256', $laws)) {
                        $newIncentiveControl = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik4447_28_sigortali.action;')
                            ->withData([
                                'isverenToken' => $tokken[1],
                            ])
                            ->allowRedirect()
                            ->setCookieJar($cookieFile)
                            ->setCookieFile($cookieFile)
                            ->get();

                        sleep(3);
                        if (strstr($newIncentiveControl, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) {
                            return response()
                                ->json([
                                    'code' => 'SUCCESS',
                                    'message' => 'İşveren Sistemi 28.Madde için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                                    'progress' => 90,
                                    'step' => 'v3_7103',
                                ]);
                        }

                        $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
                            ->allowRedirect()
                            ->setCookieJar($cookieFile)
                            ->setCookieFile($cookieFile)
                            ->post();

                        if (strstr($page_before, "[Hata Kod : x301]")) {
                            $cookieKey = startCookieV3($sgk_company);
                            return response()
                                ->json([
                                    'code' => 'LOGIN_FAIL',
                                    'message' => 'İşveren Sistemi Giriş Hatası.',
                                    'step' => 'v3_26'
                                ]);
                        }

                        $fileName = $sgk_company->id . "-" . '27256.xlsx';
                        Storage::disk('excel_incentives')->put($fileName, $page_before);
                        sleep(3);
                        $excel = \SimpleXLSX::parse(Storage::disk('excel_incentives')->path($fileName));
                        $xls = $excel->rows();
                        unset($xls[0]);
                        unset($xls[1]);

                        if (count($xls) == 0) {
        // VERİ YOK
                            return response()
                                ->json([
                                    'code' => 'SUCCESS',
                                    'message' => 'İşveren Sistemi 28.Madde için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                                    'progress' => 90,
                                    'step' => 'v3_7103',
                                ]);

                        } else {
                            foreach ($xls as $incentive) {
                                $start = trim($incentive[5]) . "/01";
                                $start = str_replace(" ", "", $start);
                                $start = str_replace('/', '-', $start);
                                $start = date("Y-m-d", strtotime($start));
                                $finish = trim($incentive[6]) . "/01";
                                $finish = str_replace(" ", "", $finish);
                                $finish = str_replace('/', '-', $finish);
                                $finish = date("Y-m-d", strtotime($finish));
                                $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                                $finish = $lastDayofMonth;


                                // JOB DATE

                                $job_start = date("Y-m-d", strtotime(trim($incentive[9])));
                                if (strstr($incentive[10], '.')) {
                                    $job_finish = date("Y-m-d", strtotime(trim($incentive[10])));
                                } else {
                                    $job_finish = "2025-02-25";
                                }

                                $save = new Incentive();
                                $save->sgk_company_id = session()->get('selectedCompany')['id'];
                                $save->tck = trim($incentive[1]);
                                $save->law = trim($incentive[8]);
                                $save->start = $start;
                                $save->finish = $finish;
                                $save->min_personel = trim($incentive[7]);
                                $save->days = 9999;
                                $save->job_start = $job_start;
                                $save->job_finish = $job_finish;

                                $save->save();


                            }
                        }
                    }


                }
        */
        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => '28.Madde İşveren Sistemi Listesi Kaydedildi',
                'progress' => 90,
                'step' => 'v3_31',
            ]);
    }

    public function v3OldEncouragementSave_31 ()
    {
        if (session()->has('options_laws')) {
            $laws = session()->get('options_laws');
            if (!in_array('7319', $laws)) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => '6111 İşveren Sistemi Listesi Atlandı',
                        'progress' => 95,
                        'step' => 'v3_7103',
                    ]);
            }
        }

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);
        $returnStatus = false;
        $tokkenData = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikTanimlama.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        preg_match('/isverenToken=(.*?)"/', $tokkenData, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_31'
                ]);
        }
        $tokken[1] = str_replace('"', '', $tokken[1]);

        $newIncentiveControl = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik4447_31_sigortali.action;')
            ->withData([
                'isverenToken' => $tokken[1],
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();



        if (strstr($newIncentiveControl, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'İşveren Sistemi 6111 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                    'progress' => 90,
                    'step' => 'v3_7103',
                ]);
        }

        $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();
        if (strstr($page_before, "[Hata Kod : x301]")) {
            $cookieKey = startCookieV3($sgk_company);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_31'
                ]);
        }
        $fileName = $sgk_company->id . "-" . '7319.xlsx';

        if (Storage::disk('excel_incentives')->exists($fileName)) {
            Storage::disk('excel_incentives')->delete($fileName);
        }
        Storage::disk('excel_incentives')->put($fileName, $page_before);
        sleep(3);
        $excel = \SimpleXLSX::parse(Storage::disk('excel_incentives')->path($fileName));
        $xls = $excel->rows();


        unset($xls[0]);
        unset($xls[1]);
        $array_count = count($xls[2]);

        foreach ($xls as $incentive) {
            $start = trim($incentive[5]) . "/01";
            $start = str_replace(" ", "", $start);
            $start = str_replace('/', '-', $start);
            $start = date("Y-m-d", strtotime($start));
            $finish = trim($incentive[6]) . "/01";
            $finish = str_replace(" ", "", $finish);
            $finish = str_replace('/', '-', $finish);
            $finish = date("Y-m-d", strtotime($finish));
            $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
            $finish = $lastDayofMonth;

            // JOB DATE
            $job_start = date("Y-m-d", strtotime(trim($incentive[11])));
            if (strstr($incentive[12], '.')) {
                $job_finish = date("Y-m-d", strtotime(trim($incentive[12])));
            } else {
                $job_finish = "2025-02-25";
            }

            $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', 7319)->where('start', $start)->where('finish', $finish)->where('sgk_company_id',$sgk_company['id'])->get()->count();
            if ($saveControl == 0) {
                $returnStatus = true;
                $save = new Incentive();
                $save->sgk_company_id = session()->get('selectedCompany')['id'];
                $save->tck = $incentive[1];
                $save->start = $start;
                $save->finish = $finish;
                $save->job_start = $job_start;
                $save->job_finish = $job_finish;
                $save->filter_status = 1;
                $save->law = 7319;
                $save->days = 9999;
                if ($incentive[7] == "-") {
                    $save->min_personel = 0;
                } else {
                    $min_personel = str_replace('.', '', trim($incentive[7]));
                    $min_personel = str_replace(',', '', $min_personel);
                    $save->min_personel = $min_personel;
                }
                $save->save();
            }
        }

        if ($returnStatus) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => '7319 İşveren Sistemi Listesi Kaydedildi',
                    'progress' => 95,
                    'step' => 'v3_7103',
                ]);
        } else {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'İşveren Sistemi 7319 için Yeni Kişi Bulunamamıştır.',
                    'progress' => 95,
                    'step' => 'v3_7103',
                ]);
        }




    }

    public function v3OldEncouragementSave_7103()
    {

        if (session()->has('options_laws')) {
            $laws = session()->get('options_laws');
            if (!in_array('17103', $laws)) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => '7103 İşveren Sistemi Listesi Kaydedildi',
                        'progress' => 90,
                        'step' => 'v4_14857'
                    ]);
            }
        }
        $returnStatus = false;
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);
        $tokkenData = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikTanimlama.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        preg_match('/isverenToken=(.*?)"/', $tokkenData, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_7103'
                ]);
        }
        $data_controll = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik4447_19_sigortali.action;')
            ->withData([
                'isverenToken' => $tokken[1],
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        if (strstr($data_controll, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) //Excel Control
        {
            $cookieKey = startCookieV3($sgk_company);
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'İşveren Sistemi 7103 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                    'progress' => 91,
                    'step' => 'v4_14857'
                ]);
        } else {
            sleep(3);
            $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();
            if (strstr($page_before, "[Hata Kod : x301]")) {
                $cookieKey = startCookieV3($sgk_company);
                return response()
                    ->json([
                        'code' => 'LOGIN_FAIL',
                        'message' => 'İşveren Sistemi Giriş Hatası.',
                        'step' => 'v3_7103'
                    ]);
            }
            $fileName = $sgk_company->id . "-" . '7103.xlsx';

            Storage::disk('excel_incentives')->put($fileName, $page_before);
            sleep(3);
            $excel = \SimpleXLSX::parse(Storage::disk('excel_incentives')->path($fileName));

            $xls = $excel->rows();

            unset($xls[0]);
            unset($xls[1]);
            $array_count = count($xls[2]);
            if ($array_count == 12) {
                foreach ($xls as $incentive) {

                    $start = trim($incentive[5]) . "/01";
                    $start = str_replace(" ", "", $start);
                    $start = str_replace('/', '-', $start);
                    $start = date("Y-m-d", strtotime($start));
                    $finish = trim($incentive[6]) . "/01";
                    $finish = str_replace(" ", "", $finish);
                    $finish = str_replace('/', '-', $finish);
                    $finish = date("Y-m-d", strtotime($finish));
                    $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                    $finish = $lastDayofMonth;
                    // JOB DATE

                    $job_start = date("Y-m-d", strtotime(trim($incentive[9])));
                    if (strstr($incentive[10], '.')) {
                        $job_finish = date("Y-m-d", strtotime(trim($incentive[10])));
                    } else {
                        $job_finish = "2025-02-25";
                    }

                    $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', trim($incentive[8]))->where('start', $start)->where('finish',$finish)->where('sgk_company_id',$sgk_company['id'])->get()->count();
                    // Save List
                    if ($saveControl == 0) {
                        $save = new Incentive();
                        $save->sgk_company_id = session()->get('selectedCompany')['id'];
                        $save->tck = trim($incentive[1]);
                        $save->start = $start;
                        $save->finish = $finish;
                        $save->job_start = $job_start;
                        $save->job_finish = $job_finish;
                        $save->filter_status = 1;
                        $save->law = trim($incentive[8]);
                        $save->days = 9999;
                        if ($incentive[7] == "-") {
                            $save->min_personel = 0;
                        } else {
                            $min_personel = str_replace('.', '', $incentive[7]);
                            $min_personel = str_replace(',', '', $min_personel);
                            $save->min_personel = $min_personel;
                        }
                        $save->save();
                    }
                }


                if ($returnStatus) {
                    SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
                    return response()
                        ->json(['code' => 'SUCCESS',
                            'message' => '7103 İşveren Sistemi Listesi Kaydedildi',
                            'progress' => 91,
                            'step' => 'v4_14857'
                        ]);
                } else {
                    $cookieKey = startCookieV3($sgk_company);
                    SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
                    return response()
                        ->json([
                            'code' => 'SUCCESS',
                            'message' => 'İşveren Sistemi 7103 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                            'progress' => 91,
                            'step' => 'v4_14857'
                        ]);
                }
            } else {
                foreach ($xls as $incentive) {
                    $start = trim($incentive[5]) . "/01";
                    $start = str_replace(" ", "", $start);
                    $start = str_replace('/', '-', $start);
                    $start = date("Y-m-d", strtotime($start));
                    $finish = trim($incentive[6]) . "/01";
                    $finish = str_replace(" ", "", $finish);
                    $finish = str_replace('/', '-', $finish);
                    $finish = date("Y-m-d", strtotime($finish));
                    $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                    $finish = $lastDayofMonth;
                    // JOB DATE

                    $job_start = date("Y-m-d", strtotime(trim($incentive[13])));
                    if (strstr($incentive[14], '.')) {
                        $job_finish = date("Y-m-d", strtotime(trim($incentive[14])));
                    } else {
                        $job_finish = "2025-02-25";
                    }

                    $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', trim($incentive[12]))->where('start', $start)->where('finish',$finish)->where('sgk_company_id',$sgk_company['id'])->get()->count();
                    // Save List
                    if ($saveControl == 0) {
                        $save = new Incentive();
                        $save->sgk_company_id = session()->get('selectedCompany')['id'];
                        $save->tck = trim($incentive[1]);
                        $save->start = $start;
                        $save->finish = $finish;
                        $save->job_start = $job_start;
                        $save->job_finish = $job_finish;
                        $save->filter_status = 1;
                        $save->law = trim($incentive[12]);
                        $save->days = 9999;
                        if ($incentive[7] == "-") {
                            $save->min_personel = 0;
                        } else {
                            $min_personel = str_replace('.', '', $incentive[7]);
                            $min_personel = str_replace(',', '', $min_personel);
                            $save->min_personel = $min_personel;
                        }
                        $save->save();
                    }
                }


                if ($returnStatus) {
                    SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
                    return response()
                        ->json(['code' => 'SUCCESS',
                            'message' => '7103 İşveren Sistemi Listesi Kaydedildi',
                            'progress' => 100,
                            'step' => 'v4_14857'
                        ]);
                } else {
                    $cookieKey = startCookieV3($sgk_company);
                    SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
                    return response()
                        ->json([
                            'code' => 'SUCCESS',
                            'message' => 'İşveren Sistemi 7103 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                            'progress' => 100,
                            'step' => 'v4_14857'
                        ]);
                }
            }
        }


    }

    public function v4OldEncouragementSave_14857()
    {
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev4);

        $data_controll = Curl::to('https://uyg.sgk.gov.tr/Sigortali_Tesvik_4a/ActionMultiplexer?aid=IT_OZR_SIG_ISL&islemturu=0')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        $data_controll = str_replace("\t", "", $data_controll);
        $data_controll = str_replace("\r", "", $data_controll);
        $data_controll = str_replace("\n", "", $data_controll);
        preg_match_all('#<font color="red"style="font-weight: bold;"> (.*?)<\/font>#', $data_controll, $output_data_controll);


        if (isset($output_data_controll[1][0]))
        {

            $cookieKey = startCookieV4($sgk_company);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v4_14857'
                ]);

        }



        $declaration1 = Declaration::where("sgk_company_id", $sgk_company->id)->orderBy('declarations_date', 'DESC')->first();

        if ($declaration1) {
            $declarations = Declaration::where("sgk_company_id", $sgk_company->id)
                ->where('declarations_date', $declaration1->declarations_date)
                ->where('document_type', '!=', 02)
                ->pluck('id');

            $declaration_services = DeclarationService::whereIn('declaration_id', $declarations)->where('v4_request', 0)->get();

            $v4progressRequest = 0;

            foreach ($declaration_services as $declaration_service) {
                $v4progressRequest = session()->get('progress') + 1;
                session(["progress" => $v4progressRequest]);
                $v4progressRequest++;
                if ($v4progressRequest == 10) {
                    return response()
                        ->json([
                            'code' => 'TIMEOUT',
                            'message' => 'Engelli Kontrol Yapılıyor.',
                            'progress' => $v4progressRequest,
                            'step' => 'v4_14857',
                        ]);
                }
                $sorgula = Curl::to('https://uyg.sgk.gov.tr/Sigortali_Tesvik_4a/ActionMultiplexer?aid=IT_OZR_SIG_ISL&islemturu=3')
                    ->withData([
                        'form_tcno' => $declaration_service->tck,
                        'form_sskno' => null,
                        'form_ad' => null,
                        'form_soyad' => null,
                        'kayitli_tcno' => null
                    ])
                    ->allowRedirect()
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->post();
                $sorgula = str_replace("\t", "", $sorgula);
                $sorgula = str_replace("\r", "", $sorgula);
                $sorgula = str_replace("\n", "", $sorgula);
                // preg_match('/<TD><FONT face="Arial Black" color="red">(.*?)<\/FONT><\/TD>/', $sorgula, $output);

                if (strstr($sorgula, "SORGULANAN")) {
                    $update = DeclarationService::where('tck',$declaration_service->tck)->update([
                        'v4_request' => true
                    ]);

                    return response()
                        ->json([
                            'code' => 'SUCCESS',
                            'message' => 'Sorgulanan Kişi %40 Engeli Yoktur.',
                            'progress' => $v4progressRequest,
                            'step' => 'v4_14857'
                        ]);
                }

                preg_match_all('/<INPUT type="text" name="form_sskno" id="form_sskno"value="(.*?)".*>/', $sorgula, $ssk_no);
                if (isset($ssk_no[1][0]))
                {
                    $ssk = $ssk_no[1][0];
                }
                else
                {
                    dd($ssk_no);
                }

                $sonuc = Curl::to('https://uyg.sgk.gov.tr/Sigortali_Tesvik_4a/ActionMultiplexer?aid=IT_OZR_SIG_ISL&islemturu=1')
                    ->withData([
                        'form_tcno' => $declaration_service->tck,
                        'form_sskno' => $ssk,
                        'form_ad' => $declaration_service->isim,
                        'form_soyad' => null,
                        'kayitli_tcno' => null
                    ])
                    ->allowRedirect()
                    ->setCookieJar($cookieFile)
                    ->setCookieFile($cookieFile)
                    ->post();

            }

            Curl::to(' https://uyg.sgk.gov.tr/Sigortali_Tesvik_4a/ActionMultiplexer?aid=IT_OZR_SIG_ISL&islemturu=0')
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->get();

            $rapors =  Curl::to('https://uyg.sgk.gov.tr/Sigortali_Tesvik_4a/ActionMultiplexer?aid=IT_OZR_SIG_ISL&islemturu=5')
                ->withData([
                    'form_tcno' => null,
                    'form_sskno' => null,
                    'form_ad' => null,
                    'form_soyad' => null,
                    'kayitli_tcno' => null
                ])
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();

            $rapors = botPageHtmlCleaner($rapors);
            /*
             preg_match_all('/<table id="thkkkTbl".*?>(.*?)<\/table>/', $rapors, $output_rapors);
             preg_match_all('/<tr class="duyuruAciklama".*?>(.*?)<\/tr>/', $rapors, $output_rapors);
            */

            preg_match_all('/<tr class="duyuruAciklama" style="background-color: aliceblue;" >(.*?)<tr>/', $rapors, $output_rapors);
            if (isset($output_rapors[1][0]))
            {
                $datas = explode('<td align="center">',$output_rapors[1][0]);
                $i = 0;
                $satir = 0;
                foreach ($datas as $key => $data)
                {

                    if ($key > 0)
                    {
                        $degerler[$satir][] = strip_tags($data);
                        $i ++;
                        if ($i == 8)
                        {
                            $i = 0;
                            $satir++;
                        }
                    }

                }
                if (isset($degerler)) {

                    foreach ($degerler as $key => $deger) {
                        $start_date = [];
                        $finish_date = [];
                        $start_date = explode(' ', $deger[4]);
                        $finish_date = explode(' ', $deger[5]);


                        if ($start_date[0] != '-') {

                            $start = trim($start_date[3]) . '-' . trim($start_date[0]) . '-01';

                        }
                        else
                        {
                            $start = '2000-01-01';

                        }

                        if ($finish_date[0] != '-' )
                        {
                            $finish = trim($finish_date[1]) . '-' . trim($finish_date[0]) . '-31';
                        }
                        else
                        {
                            $finish = '2030-01-01';
                        }

                        $simdiki_tarih = Carbon::now();
                        $ileriki_tarih = Carbon::parse($finish);
                        $gun_farki = $simdiki_tarih->diffInDays($ileriki_tarih, false);
                        $saveControl = Incentive::where('tck', trim($deger[0]))->where('law',14857)->where('start', $start)->where('finish', $finish)->where('sgk_company_id', $sgk_company['id'])->get()->count();
                        if ($saveControl == 0) {
                            $returnStatus = true;
                            $save = new Incentive();
                            $save->sgk_company_id = session()->get('selectedCompany')['id'];
                            $save->tck = $deger[0];
                            $save->start = $start;
                            $save->min_personel = 0;
                            $save->finish = $finish;
                            $save->filter_status = 1;
                            $save->law = 14857;
                            $save->days = 9999;
                            $save->save();
                        }
                    }
                    $cookieKey = startCookieV4($sgk_company);
                    SgkCompany::where('id', $sgk_company->id)->update(['cookiev4' => null, 'cookiev4_status' => false]);
                    return response()
                        ->json([
                            'code' => 'FINISH',
                            'message' => 'İşlemler Yapıldı!',
                            'progress' => 100,
                            'url' => route('declarations.incentives.current_incentives')
                        ]);

                }
                $cookieKey = startCookieV4($sgk_company);
                SgkCompany::where('id', $sgk_company->id)->update(['cookiev4' => null, 'cookiev4_status' => false]);
                return response()
                    ->json([
                        'code' => 'FINISH',
                        'message' => 'İşlemler Yapıldı!',
                        'progress' => 100,
                        'url' => route('declarations.incentives.current_incentives')
                    ]);


            }
            else
            {
                $cookieKey = startCookieV4($sgk_company);
                SgkCompany::where('id', $sgk_company->id)->update(['cookiev4' => null, 'cookiev4_status' => false]);
                return response()
                    ->json([
                        'code' => 'FINISH',
                        'message' => 'İşlemler Yapıldı!',
                        'progress' => 100,
                        'url' => route('declarations.incentives.current_incentives')
                    ]);

            }
        }
        $cookieKey = startCookieV4($sgk_company);
        SgkCompany::where('id', $sgk_company->id)->update(['cookiev4' => null, 'cookiev4_status' => false]);
        return response()
            ->json([
                'code' => 'FINISH',
                'message' => 'İşlemler Yapıldı!',
                'progress' => 100,
                'url' => route('declarations.incentives.current_incentives')
            ]);


    }


    /*
    public function v3OldEncouragementSave_7316()
    {
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);
        $returnStatus = false;
        $tokkenData = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikTanimlama.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();

        preg_match('/isverenToken=(.*?)"/', $tokkenData, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_7316'
                ]);
        }
        $tokken[1] = str_replace('"', '', $tokken[1]);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_7316'
                ]);
        }
        $tokken[1] = str_replace('"', '', $tokken[1]);

        $newIncentiveControl = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik4447_30_sigortali.action;')
            ->withData([
                'isverenToken' => $tokken[1],
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();



        if (strstr($newIncentiveControl, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) {
            return response()
                ->json([
                    'code' => 'SUCCESS',
                    'message' => 'İşveren Sistemi 7316 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                    'progress' => 90,
                    'step' => 'v3_6111',
                ]);
        }

        $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();
        if (strstr($page_before, "[Hata Kod : x301]")) {
            $cookieKey = startCookieV3($sgk_company);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_7316'
                ]);
        }
        $fileName = $sgk_company->id . "-" . '7316.xlsx';

        if (Storage::disk('excel_incentives')->exists($fileName)) {
            Storage::disk('excel_incentives')->delete($fileName);
        }
        Storage::disk('excel_incentives')->put($fileName, $page_before);
        sleep(3);
        $excel = \SimpleXLSX::parse(Storage::disk('excel_incentives')->path($fileName));

        $xls = $excel->rows();


        unset($xls[0]);
        unset($xls[1]);
        $array_count = count($xls[2]);

        if ($array_count == 11)
        {
            foreach ($xls as $incentive) {
                $start = trim($incentive[5]) . "/01";
                $start = str_replace(" ", "", $start);
                $start = str_replace('/', '-', $start);
                $start = date("Y-m-d", strtotime($start));
                $finish = trim($incentive[6]) . "/01";
                $finish = str_replace(" ", "", $finish);
                $finish = str_replace('/', '-', $finish);
                $finish = date("Y-m-d", strtotime($finish));
                $lastDayofMonth = \Carbon\Carbon::parse($finish)->endOfMonth()->toDateString();
                $finish = $lastDayofMonth;

                // JOB DATE
                $job_start = date("Y-m-d", strtotime(trim($incentive[8])));
                if (strstr($incentive[9], '.')) {
                    $job_finish = date("Y-m-d", strtotime(trim($incentive[9])));
                } else {
                    $job_finish = "2025-02-25";
                }

                $saveControl = Incentive::where('tck', trim($incentive[1]))->where('law', 7316)->where('start', $start)->get()->count();
                if ($saveControl == 0) {
                    $returnStatus = true;
                    $save = new Incentive();
                    $save->sgk_company_id = session()->get('selectedCompany')['id'];
                    $save->tck = $incentive[1];
                    $save->start = $start;
                    $save->finish = $finish;
                    $save->job_start = $job_start;
                    $save->job_finish = $job_finish;
                    $save->filter_status = 1;
                    $save->law = 7316;
                    $save->days = 9999;
                    $save->min_personel = 0;
                    $save->save();
                }
            }

            if ($returnStatus) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => '7316 İşveren Sistemi Listesi Kaydedildi',
                        'progress' => 90,
                        'step' => 'v3_3294',
                    ]);
            } else {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => 'İşveren Sistemi 7316 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                        'progress' => 90,
                        'step' => 'v3_3294',
                    ]);
            }
        }



    }
*/


// LOGIN CONTROLLER

    public function loginv2Post(Request $request)
    {

        $sgk_company = getSgkCompany();
        $captcha = $request->captcha;

        $result = sysloginv2($sgk_company, $captcha);


        if ($result) {
            return response()->json(['code' => 'LOGIN_OK', 'message' => 'Login Success']);
        } else {
            $cookieKey = startCookieV2($sgk_company);
            return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Fail', 'key' => $cookieKey]);
        }
    }

    public function loginv3Post(Request $request)
    {
        $sgk_company = getSgkCompany();
        $captcha = $request->captcha;
        $result = sysloginv3($sgk_company, $captcha);
        if ($result) {
            return response()->json(['code' => 'LOGIN_OK', 'message' => 'Login Success',
                'progress' => 50]);
        } else {
            $cookieKey = startCookieV3($sgk_company);
            return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Fail', 'key' => $cookieKey,
                'progress' => 50]);
        }
    }

    public function loginv4Post(Request $request)
    {
        $sgk_company = getSgkCompany();
        $captcha = $request->captcha;
        $result = sysloginv4($sgk_company, $captcha);

        if ($result) {
            return response()->json(['code' => 'LOGIN_OK', 'message' => 'Login Success',
                'progress' => 92]);
        } else {
            $cookieKey = startCookieV4($sgk_company);
            return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Fail', 'key' => $cookieKey,
                'progress' => 50]);
        }
    }

    public function potentialStore(Request $request)
    {
        $sgk_company = getSgkCompany();
        $potential["tck"] = $request->tck;
        session(["PotentialList" => $potential]);

        return view("incentives.potential_incitement_last", compact('request'));
    }

    public function v3Potential()
    {
        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiev3);
        $loginProcedure = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/internetLinkTesvikPotansiyel.action;')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();

        preg_match('/isverenToken=(.*?)"/', $loginProcedure, $tokken);
        if (!isset($tokken[1])) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İşveren Sistemi Giriş Hatası.',
                    'step' => 'v3_potential'
                ]);
        }

        $tck = session()->get('PotentialList')['tck'];
        $tesvikSorgulama = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/uygunTesvikSorgula.action')
            ->withHeader('Referer: https://uyg.sgk.gov.tr/YeniSistem')
            ->withData([
                'tcKimlikNo' => $tck,
                'egitimDurumu' => 1,
                'kazanc' => '3577,50',
                'isverenToken' => $tokken[1]
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();

        $tesvikSorgulama = html_entity_decode($tesvikSorgulama);
        $tesvikSorgulama = str_replace("\t", "", $tesvikSorgulama);
        $tesvikSorgulama = str_replace("\r", "", $tesvikSorgulama);
        $tesvikSorgulama = str_replace("\n", "", $tesvikSorgulama);
        $tesvikSorgulama = str_replace("#", "", $tesvikSorgulama);
        $tesvikSorgulama = str_replace("  ", "", $tesvikSorgulama);
        $tesvikSorgulama = str_replace(" TL", "", $tesvikSorgulama);
        preg_match_all('#<table id="dataTable".*?>(.*?)<\/table>#', $tesvikSorgulama, $table);
        $listArray = [];
        foreach ($table[1] as $tab) {
            $list = [];
            preg_match_all('#<p>(.*?)<\/p>#', $tab, $p);
            foreach ($p[1] as $value) {
                $list[] = trim($value);
            }
            $listArray[] = $list;
        }

        session(["potential_icitement_result" => $table[1]]);
        SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
        return response()
            ->json([
                'code' => 'FINISH',
                'message' => 'Tamamlandı. Sonuçlar görüntüleme için hazırlanıyor.',
                'progress' => 100,
                'url' => route('declarations.incentives.potential')
            ]);
    }
}
