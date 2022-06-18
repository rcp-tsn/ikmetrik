<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Declaration;
use App\Models\DeclarationService;
use App\Models\LeakIncentive;
use App\Models\LeakApprovedIncentive;
use App\Models\LeakGainIncentive;
use App\Models\LeakIncentiveService;
use App\Models\LeakSgkCompany;
use App\Models\SgkCompany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Curl;
use Illuminate\Support\Facades\Storage;
use Gufy\PdfToHtml\Pdf;
use Illuminate\View\View;
use DateTime;
class LostLeakController extends Controller
{

    public function index()
    {

        $company = session()->get('selectedCompany');

        $company = SgkCompany::where('id', $company['id'])->first();
        SgkCompany::where('id', $company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
      //  $cookieKey = startCookieV2($company);

        return view('losts.index');
    }

    public function v2TahakkukDate()
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
                        'step' => 'v2_tahakkuk_date'
                    ]);
            }
            $expFounded = explode("/", $founded[1]);
            $founded = $expFounded[2] . "-" . $expFounded[1] . "-" . $expFounded[0];
            SgkCompany::where('id', $sgk_company->id)->update(['founded' => $founded]);
        }

        preg_match_all('#<option value="(.*?)">.*?<\/option>#', $lastMounth, $dates);

        foreach ($dates[0] as $key => $date)
        {
            $a =   explode(' ' , $date);
            $newstr = strstr($a[1], '"');
            $deger =  explode('"',$newstr);

            if ($deger[1] == -1)
            {
                $keys[] = $key ;
            }
        }

        foreach ($dates[0] as $key => $newdate)
        {
            if ($key > $keys[0] and $key < $keys[1])
            {
                $dates2[] = $newdate;
            }

        }


        if (isset($dates2))
        {
            session(['dates'=>null]);
            session(['dates2' => $dates2]);
            return response()
                ->json([
                    'code' => 'DATE',
                    'message' => 'Tahakkuk Tarihlerini Giriniz',
                    'progress' => '10',
                ]);


        }
        else
            {
                return response()
                    ->json([
                        'code' => 'ERROR',
                        'message' => 'Tahakkuk Tarihleri Çekilemedi',
                        'progress' => '5',
                        'step'=> 'v2_tahakkuk_date',
                    ]);
            }

    }

    public function v2tahakkukList(Request $request)
    {
        $sgk_company = getSgkCompany();
        session(["one_date" => $request->one_date]);
        session(["two_date" => $request->two_date]);

        if ($request->one_date > -1) {

                    $sgk_company = getSgkCompany();
                    $cookieFile = cookieFileName($sgk_company->cookiev2);

                   $lastMounth =  Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemBilgileriniYukle.action')
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
                            'step' => 'v2_tahakkuk_date'
                        ]);
                }
                $expFounded = explode("/", $founded[1]);
                $founded = $expFounded[2] . "-" . $expFounded[1] . "-" . $expFounded[0];
                SgkCompany::where('id', $sgk_company->id)->update(['founded' => $founded]);
            }

                    $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/tahakkukonaylanmisTahakkukDonemSecildi.action')
                        ->withData([
                            'struts.token.name' => 'token',
                            'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                            'hizmet_yil_ay_index' => $request->one_date,
                            'hizmet_yil_ay_index_bitis' => $request->two_date
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
                        $control = LeakApprovedIncentive::where('document_no', '=', $value[8])->select('id')->first();

                        if (!$control) {

                            $date1 = strtotime($value[0]);
                            $date2 = strtotime('2011-03-01');
                            //$day = ($date1 >= $date2) ;
                            if ($date1 >= $date2) {
                                $save = new LeakApprovedIncentive();
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
                                'message' => 'Tahakkuk listeleri okundu.',
                                'progress' => '20',
                                'step' => 'pdf_download',
                            ]);
                    }
                    else
                    {

                    }
        }

          else
            {
                return back()->with('error', 'Tarih Seçimleri Hatalı');
            }

    }

    public function v2pdfdownload()
    {
        $one_date = session()->get('one_date');
        $two_date = session()->get('two_date');
        $sgk_company = getSgkCompany();


            $timeOutLimit = rand(10, 15);
            $limit = 0;
            $sgk_company = getSgkCompany();
            $cookieFile = cookieFileName($sgk_company->cookiev2);
            $ApprovedIncentive = LeakApprovedIncentive::where("sgk_company_id", $sgk_company->id)
                ->whereIn('pdf_download', [0, 3])
                ->get();
            foreach ($ApprovedIncentive as $value) {

                $fileName = $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $value->document_no . DIRECTORY_SEPARATOR . $value->document_no . ".pdf";
                $fileStatus = 0;
                if ($value->file_download == 0) {
                    $fileControl = Storage::disk('leak_pdfs')->exists($fileName);
                    if ($fileControl) {
                        $size = Storage::disk('leak_pdfs')->size($fileName);
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
                                'step' => 'pdf_download',
                            ]);
                    }
                    $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/pdfGosterim.action')
                        ->withData([
                            'struts.token.name' => 'token',
                            'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                            'tip' => 'tahakkukonayliFisHizmetPdf',
                            'download' => 'false',
                            'hizmet_yil_ay_index' => $one_date,
                            'hizmet_yil_ay_index_bitis' => $two_date,
                            'bildirgeRefNo' => $value->document_no
                        ])
                        ->allowRedirect()
                        ->setCookieJar($cookieFile)
                        ->setCookieFile($cookieFile)
                        ->post();
                    Storage::disk("leak_pdfs")->put($fileName, $response);
                    $fileStatus = 1;
                }
                // Save Status
                $change = LeakApprovedIncentive::find($value->id);
                $change->pdf_download = $fileStatus;
                $change->save();
            }


            //kazanç hesaplamak için
            $timeOutGainLimit = rand(10, 15);
            $gainLimit = 0;
            $month = Carbon::now()->subMonth(2);
            $search_date = $month->format('Y-m') . '-01';
            //echo $search_date;die();
            $gainApprovedIncentive = LeakApprovedIncentive::where("sgk_company_id", $sgk_company->id)
                ->whereIn('gain_download', [0, 3])
                ->whereIn('law_no', [17103, 27103, 6111, 5510, 14857, 7252])
                ->where('accrual', '>=', '2020-01-01')
                ->get();
            foreach ($gainApprovedIncentive as $gain_value) {

                $gainFileName = $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $gain_value->document_no . DIRECTORY_SEPARATOR . $gain_value->document_no . "_gain.pdf";
                $gainFileStatus = 0;
                if ($gain_value->gain_download == 0) {
                    $fileControl = Storage::disk('leak_pdfs')->exists($gainFileName);
                    if ($fileControl) {
                        $size = Storage::disk('leak_pdfs')->size($gainFileName);
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
                                'step' => 'pdf_download',
                            ]);
                    }
                    $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/tahakkuk/pdfGosterim.action')
                        ->withData([
                            'struts.token.name' => 'token',
                            'token' => '9I696UIPZGGULPKHSG92OJLGL4H3BCTT',
                            'tip' => 'tahakkukonayliFisTahakkukPdf',
                            'download' => 'false',
                            'hizmet_yil_ay_index' => $one_date,
                            'hizmet_yil_ay_index_bitis' => $two_date,
                            'bildirgeRefNo' => $gain_value->document_no
                        ])
                        ->allowRedirect()
                        ->setCookieJar($cookieFile)
                        ->setCookieFile($cookieFile)
                        ->post();
                    Storage::disk("leak_pdfs")->put($gainFileName, $response);
                    $gainFileStatus = 1;
                }
                // Save Status
                $change = LeakApprovedIncentive::find($gain_value->id);
                $change->gain_download = $gainFileStatus;
                $change->save();
            }
            if ($ApprovedIncentive->count() == 0 && $gainApprovedIncentive->count() == 0) {
                return response()
                    ->json([
                        'code' => 'SUCCESS',
                        'message' => 'Tahakkuk listeleri indirildi.',
                        'progress' => 20,
                        'step' => 'pdf_parse',
                    ]);
            } else {
                $progress = session()->get('progress') + 1;
                session(["progress" => $progress]);
                return response()
                    ->json([
                        'code' => 'TIMEOUT',
                        'message' => 'Tahakkuk listeleri indiriliyor.',
                        'progress' => $progress,
                        'step' => 'pdf_download',
                    ]);
                }
            }

    public function v2PdfParse()
    {

        $sgk_company = getSgkCompany();

            $timeOutLimit = rand(3, 5);
            $limit = 0;
            $ApprovedIncentive = LeakApprovedIncentive::where("sgk_company_id", $sgk_company->id)
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
                            'step' => 'pdf_parse',
                        ]);
                }
                $documentNo = $value->document_no;
                $readfile = storage_path('app' . DIRECTORY_SEPARATOR . 'leak_pdfs' . DIRECTORY_SEPARATOR . $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $documentNo . DIRECTORY_SEPARATOR . $documentNo . '.pdf');

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
                                $control = LeakIncentiveService::where("approved_incentive_id", $value->id)->select('id')
                                    ->where("tck", $personel[1])
                                    ->where("meslek_kod", isset($personel[13 + $plus]) ? $personel[13 + $plus] : 0)
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
                                    if ($personel[1] != 0 )
                                    {
                                        $save = new LeakIncentiveService();
                                        $save->approved_incentive_id = $value->id;
                                        $save->sgk_company_id = $value->sgk_company_id;
                                        $save->accrual = $value->accrual;
                                        $save->tck = $personel[1];
                                        $save->isim = $personel[2];
                                        $save->soyisim = $personel[3];
                                        if (count($personel) == 14) {
                                            $save->kizlik_soyadi = null;
                                        }
                                        $save->ucret_tl = decimalFormatMysql($personel[4]);
                                        $save->ikramiye_tl = decimalFormatMysql($personel[5]);
                                        $save->gun = $personel[6];
                                        $save->eksik_gun = $personel[7 + $plus];
                                        $save->job_start = $job_start;
                                        $save->job_finish = $job_finish;
                                        $save->icn = $personel[10 + $plus];
                                        $save->egn = isset($personel[11 + $plus ]) ? $personel[11 + $plus] : 0;
                                        if (isset($personel[13])) {
                                            $save->meslek_kod = $personel[13];
                                        } else {
                                        }
                                        $save->save();
                                    }

                                }
                            }
                        }
                    }
                }

                LeakApprovedIncentive::where('id', $value->id)->update([
                    'pdf_parse' => 1,
                ]);
            }


            //KAZANÇ İÇİN
            $gain = [];
            $gainTimeOutLimit = rand(5, 10);
            $gainLimit = 0;
            $gainApprovedIncentive = LeakApprovedIncentive::where("sgk_company_id", $sgk_company->id)
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
                            'step' => 'pdf_parse',
                        ]);
                }
                $documentNo = $gain_value->document_no;
                $readfile = storage_path('app' . DIRECTORY_SEPARATOR . 'leak_pdfs' . DIRECTORY_SEPARATOR . $sgk_company->company_username . '-' . $sgk_company->company_usercode . DIRECTORY_SEPARATOR . $documentNo . DIRECTORY_SEPARATOR . $documentNo . '_gain.pdf');
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
                                    $has_gain = LeakGainIncentive::where('sgk_company_id', $sgk_company->id)
                                        ->where('approved_incentive_id', $gain_value->id)
                                        ->where('accrual', $gain_value->accrual)->first();
                                    if ($has_gain) {
                                        $save = $has_gain;
                                    } else {
                                        $save = new LeakGainIncentive();
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

                                    $has_gain = LeakGainIncentive::where('sgk_company_id', $sgk_company->id)
                                        ->where('approved_incentive_id', $gain_value->id)
                                        ->where('accrual', $gain_value->accrual)->first();
                                    if ($has_gain) {
                                        $save = $has_gain;
                                    } else {
                                        $save = new LeakGainIncentive();
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
                                    $has_gain = LeakGainIncentive::where('sgk_company_id', $sgk_company->id)
                                        ->where('approved_incentive_id', $gain_value->id)
                                        ->where('accrual', $gain_value->accrual)->first();
                                    if ($has_gain) {
                                        $save = $has_gain;
                                    } else {
                                        $save = new LeakGainIncentive();
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
                                    $has_gain = LeakGainIncentive::where('sgk_company_id', $sgk_company->id)
                                        ->where('approved_incentive_id', $gain_value->id)
                                        ->where('accrual', $gain_value->accrual)->first();
                                    if ($has_gain) {
                                        $save = $has_gain;
                                    } else {
                                        $save = new LeakGainIncentive();
                                    }
                                    $save->sgk_company_id = $sgk_company->id;
                                    $save->approved_incentive_id = $gain_value->id;
                                    $save->accrual = $gain_value->accrual;
                                    $save->law_6111 = $save->law_6111 + decimalFormatMysql($gain_html[$key + 1]);
                                    $save->save();
                                }

                            } elseif (strstr($html_gain, "103 SAYILI KANUNDAN")) {
                                if (isset($gain_html[$key + 1])) {

                                    $has_gain = LeakGainIncentive::where('sgk_company_id', $sgk_company->id)
                                        ->where('approved_incentive_id', $gain_value->id)
                                        ->where('accrual', $gain_value->accrual)->first();
                                    if ($has_gain) {
                                        $save = $has_gain;
                                    } else {
                                        $save = new LeakGainIncentive();
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
                }


                $has_gain = LeakGainIncentive::where('sgk_company_id', $sgk_company->id)
                    ->where('approved_incentive_id', $gain_value->id)
                    ->where('accrual', $gain_value->accrual)->first();
                if ($has_gain) {
                    $save = $has_gain;
                    $save->total_amount = $save->total_amount + array_sum($oran_tutar);
                    $save->save();
                }
                LeakApprovedIncentive::where('id', $gain_value->id)->update([
                    'gain_parse' => 1,
                ]);
            }
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
            //KAZANÇ BİTTİ

        SgkCompany::where('id', $sgk_company->id)->update(['cookiev2' => null, 'cookiev2_status' => false]);
        //KAZANÇ BİTTİ
        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => 'Tahakkuk listeleri okundu.',
                'progress' => 40,
                'step' => 'v3_newRequest',
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
                    'step' => 'v3_newRequest'
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

        $declaration1 = LeakApprovedIncentive::where("sgk_company_id", $sgk_company->id)
            ->where('law_no',5510)
             ->first();


        if ($declaration1) {

            $declaration1 = LeakApprovedIncentive::where("sgk_company_id", $sgk_company->id)
                ->where('law_no',5510)
                ->get()->pluck('id');

            $declaration_services = LeakIncentiveService::where("sgk_company_id", $sgk_company->id)
                ->whereIn('approved_incentive_id', $declaration1)
                ->where('history_request',0)
                ->get();

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
                            'step' => 'v3_newRequest',
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
                                'tcKimlikNo' => $declaration_service->tck,
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

                        $errorss[$sgk_company->id][$declaration_service->tck] = array('0'=>$error[1],'1'=>$declaration_service->isim.' '.$declaration_service->soyisim);
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


                        foreach ($table[1] as $tab) {
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

                            $laws_taranacak = [6111,7103,7252,17103];
                            foreach ($laws_taranacak as $law) {
                                if (in_array($law, $laws)) {
                                    dd('ok');
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
                $declaration_services2 = LeakIncentiveService::where('approved_incentive_id', $declaration_service->approved_incentive_id)->where('tck', $declaration_service->tck)->first();
                $declaration_services2->history_request = 1;
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
                'step' => 'v3_6111',
            ]);

    }

    public function v3OldEncouragementSave_6111()
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
           return redirect(route('lost.v3611'));
        }
        $fileName = $sgk_company->id . "-" . '6111.xlsx';

        if (Storage::disk('leak_excel_incentives')->exists($fileName)) {
            Storage::disk('leak_excel_incentives')->delete($fileName);
        }
        Storage::disk('leak_excel_incentives')->put($fileName, $page_before);
        sleep(3);
        $excel = \SimpleXLSX::parse(Storage::disk('leak_excel_incentives')->path($fileName));
        $xls = $excel->rows();


        unset($xls[0]);
        unset($xls[1]);
        $array_count = count($xls[2]);

        if ($array_count == 12)
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
                if (strstr($incentive[10], '.')) {
                    $job_finish = date("Y-m-d", strtotime(trim($incentive[10])));
                } else {
                    $job_finish = "2025-02-25";
                }
/*
 * işten ayrılanları cıkarmak için yaptık
                $now = date('Y/m/d');
                $ilk = strtotime($now);
                $son = strtotime($finish);
                if ($ilk - $son > 0) {
                   $interval =  0;
                } else {
                    $interval =  1;
                }
*/


                $saveControl = LeakIncentive::where('tck', trim($incentive[1]))->where('law', 6111)->where('start', $start)->get()->count();
                if ($saveControl == 0) {

                        $returnStatus = true;
                        $save = new LeakIncentive();
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
        else {


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

                $now = date('Y/m/d');
                $ilk = strtotime($now);
                $son = strtotime($finish);
                if ($ilk - $son > 0) {
                    $interval =  0;
                } else {
                    $interval =  1;
                }

                $saveControl = LeakIncentive::where('tck', trim($incentive[1]))->where('law', 6111)->where('start', $start)->get()->count();
                if ($saveControl == 0) {



                        $returnStatus = true;
                        $save = new LeakIncentive();
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
                    Storage::disk('leak_excel_incentives')->put($fileName, $page_before);
                    sleep(3);
                    $excel = \SimpleXLSX::parse(Storage::disk('leak_excel_incentives')->path($fileName));

                    $xls = $excel->rows();

                    unset($xls[0]);
                    unset($xls[1]);
                    if(count($xls) == 0) {
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



                            $saveControl = LeakIncentive::where('tck', trim($incentive[1]))->where('law', trim($incentive[9]))->where('start', $start)->get()->count();
                            if ($saveControl == 0) {

                                    $save = new LeakIncentive();
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

        return response()
            ->json([
                'code' => 'SUCCESS',
                'message' => '28.Madde İşveren Sistemi Listesi Kaydedildi',
                'progress' => 90,
                'step' => 'v3_7103',
            ]);
    }

    public function v3OldEncouragementSave_7103()
    {

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
        $data_controll =  Curl::to('https://uyg.sgk.gov.tr/YeniSistem/Isveren/tesvik4447_19_sigortali.action;')
            ->withData([
                'isverenToken' => $tokken[1],
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();
        if(strstr($data_controll, "LİSTELENECEK VERİ BULUNAMAMIŞTIR.")) //Excel Control
        {
            $cookieKey = startCookieV3($sgk_company);
            SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
            return response()
                ->json([
                    'code' => 'FINISH',
                    'message' => 'İşveren Sistemi 7103 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                    'progress' => 100,
                    'url' => 'http://tesvik3.ikmetrik.com/colculation_leak',
                ]);
        }
        else
        {
            sleep(3);
            $page_before = Curl::to('https://uyg.sgk.gov.tr/YeniSistem/ListelemManager/excelCiktiIslemi.action')
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();
            if (strstr($page_before, "[Hata Kod : x301]")) {
                $cookieKey = startCookieV3($sgk_company);
                return redirect(route('lost.v37103'));
            }
            $fileName = $sgk_company->id . "-" . '7103.xlsx';

            Storage::disk('leak_excel_incentives')->put($fileName, $page_before);
            sleep(3);
            $excel = \SimpleXLSX::parse(Storage::disk('leak_excel_incentives')->path($fileName));

            $xls = $excel->rows();

            unset($xls[0]);
            unset($xls[1]);
            $array_count = count($xls[2]);
            if ($array_count == 12)
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

                    $job_start = date("Y-m-d", strtotime(trim($incentive[9])));
                    if (strstr($incentive[10], '.')) {
                        $job_finish = date("Y-m-d", strtotime(trim($incentive[10])));
                    } else {
                        $job_finish = "2025-02-25";
                    }

                    $now = date('Y/m/d');
                    $ilk = strtotime($now);
                    $son = strtotime($finish);
                    if ($ilk - $son > 0) {
                        $interval =  0;
                    } else {
                        $interval =  1;
                    }

                    $saveControl = LeakIncentive::where('tck', trim($incentive[1]))->where('law', trim($incentive[8]))->where('start', $start)->get()->count();
                    // Save List
                    if ($saveControl == 0) {

                            $save = new LeakIncentive();
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
                        ->json(['code' => 'FINISH',
                            'message' => '7103 İşveren Sistemi Listesi Kaydedildi',
                            'progress' => 100,
                            'url' => route('leak_calculation')]);
                } else {
                    $cookieKey = startCookieV3($sgk_company);
                    SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
                    return response()
                        ->json([
                            'code' => 'FINISH',
                            'message' => 'İşveren Sistemi 7103 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                            'progress' => 100,
                            'url' => route('leak_calculation')
                        ]);
                }
            }
            else
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

                    $job_start = date("Y-m-d", strtotime(trim($incentive[13])));
                    if (strstr($incentive[14], '.')) {
                        $job_finish = date("Y-m-d", strtotime(trim($incentive[14])));
                    } else {
                        $job_finish = "2025-02-25";
                    }

                    $now = date('Y/m/d');
                    $ilk = strtotime($now);
                    $son = strtotime($finish);
                    if ($ilk - $son > 0) {
                        $interval =  0;
                    } else {
                        $interval =  1;
                    }

                    $saveControl = LeakIncentive::where('tck', trim($incentive[1]))->where('law', trim($incentive[12]))->where('start', $start)->get()->count();
                    // Save List
                    if ($saveControl == 0) {


                            $save = new LeakIncentive();
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
                        ->json([
                            'code' => 'FINISH',
                            'message' => 'İşveren Sistemi 7103 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                            'progress' => 100,
                            'url' => 'http://tesvik3.ikmetrik.com/colculation_leak',
                        ]);
                } else {
                    $cookieKey = startCookieV3($sgk_company);
                    SgkCompany::where('id', $sgk_company->id)->update(['cookiev3' => null, 'cookiev3_status' => false]);
                    return response()
                        ->json([
                            'code' => 'FINISH',
                            'message' => 'İşveren Sistemi 7103 için LİSTELENECEK VERİ BULUNAMAMIŞTIR.',
                            'progress' => 100,
                            'url' => 'http://tesvik3.ikmetrik.com/colculation_leak',
                        ]);
                }
            }
        }



    }



}
