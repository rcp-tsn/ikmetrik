<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\ApprovedIncentive;
use App\Models\Declaration;
use App\Models\DeclarationInfo;
use App\Models\DeclarationService;
use App\Models\IdentityNotification;
use App\Models\IncentiveService;
use App\Models\SgkCompany;
use App\Models\WorkAccident;
use App\Models\WorkVizite;
use Carbon\Carbon;
use Curl;
use FilterGenerator;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;


class TestController extends Controller
{

    public function __construct()
    {

    }

    public function isKazasi()
    {
        session()->forget('excel_users');
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        $work_accidents = WorkAccident::where('sgk_company_id', $sgk_company->id)->orderBy('kaza_tarihi', 'DESC')->get();
        return view('reports.is_kazasi', compact('sgk_company', 'work_accidents'));
    }

    public function IsKazasiCek()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookieIsKazasi);
        $getFirst = Curl::to('https://kesenek.sgk.gov.tr/IsvBildirimFormu/bfGirisGoruntuleme.do')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();

        $getFirst = botPageHtmlCleaner($getFirst);

        if (strstr($getFirst, "Kontrol edip tekrar deneyiniz")) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookieIsKazasi' => null, 'cookieIsKazasi_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İŞ KAZASI MESLEK HASTALIĞI E-BİLDİRİM Sistemi Giriş Hatası.',
                    'step' => 'is_-azasi',
                ]);
        }

        if (strstr($getFirst, "isyeriSifresi")) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookieIsKazasi' => null, 'cookieIsKazasi_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'İŞ KAZASI MESLEK HASTALIĞI E-BİLDİRİM Sistemi Giriş Hatası.',
                    'step' => 'is-kazasi',
                ]);
        }

        if (strstr($getFirst, "veri bulunamadı.")) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookieIsKazasi' => null, 'cookieIsKazasi_status' => false]);
            return response()
                ->json([
                    'code' => 'FINISH',
                    'message' => "İŞ kazası bulunamadı!",
                    'progress' => 100,
                    'url' => route('declarations.incentives.is-kazasi')
                ]);

        }

        preg_match_all('/<tr class="row1".*?>(.*?)<\/tr>/m', $getFirst, $parseIsKazalari, PREG_SET_ORDER, 0);

        //preg_match('/<tr class="row1">(.*?)<\/tr>/', $parseIsKazalari[1], $parseIsKazalariSon);
        //preg_match_all("#<tr class=\"row1\"'>(.*?)<\/tr>#", $getFirst, $parseIsKazalari);

        $records = [];
        $i = 1;
        foreach ($parseIsKazalari as $parseIsKaza) {
            $records = [];
              if ($i > 1) {
                  $k = 0;
                  preg_match_all('/<td.*?>(.*?)<\/td>/m', $parseIsKaza[1], $rows, PREG_SET_ORDER, 0);
                  foreach($rows as $row) {
                      if ($k <= 6) {
                          $records[] = trim($row[1]);
                      }
                      $k++;
                  }
                  /*
                  0 => "20120811638" kaza no
                  1 => "27.08.2012" olay tarihi
                  2 => "1"->kişi sayısı
                  3 => "34180445818"  -->tck
                  4 => "EMRAH"
                  5 => "PAKNA"
                  6 => "27-08-2012 21:59:08" -->bildirim
                   */
                  if (count($records) == 7) {
                      $has = WorkAccident::where('sgk_company_id', $sgk_company->id)->where('kaza_no', $records[0])->first();
                      if (!$has) {
                          $saved = new WorkAccident();
                          $saved->sgk_company_id = $sgk_company->id;
                          $saved->kaza_no = $records[0];
                          $kaza_tarihi_array = explode('.', $records[1]);
                          if (count($kaza_tarihi_array) == 3) {
                              $saved->kaza_tarihi = $kaza_tarihi_array[2].'-'.$kaza_tarihi_array[1].'-'.$kaza_tarihi_array[0];
                          } else {
                              $saved->kaza_tarihi = null;
                          }
                          $saved->kisi_sayisi = intval($records[2]);
                          $saved->tck = $records[3];
                          $saved->isim = $records[4];
                          $saved->soyisim = $records[5];

                          $bildirim_zamani_array = explode(' ', $records[6]);
                          if (count($bildirim_zamani_array) == 2) {
                              $bildirim_zamani_array2 = explode('-', $bildirim_zamani_array[0]);

                              if (count($bildirim_zamani_array2) == 3) {
                                  $saved->bildirim_zamani = $bildirim_zamani_array2[2].'-'.$bildirim_zamani_array2[1].'-'.$bildirim_zamani_array2[0].' '.$bildirim_zamani_array[1];
                              } else {
                                  $saved->bildirim_zamani = null;
                              }

                          } else {
                              $saved->bildirim_zamani = null;
                          }
                          $saved->save();
                      }
                  }
              }
              $i++;
        }


        SgkCompany::where('id', $sgk_company->id)->update(['cookieIsKazasi' => null, 'cookieIsKazasi_status' => false]);
        return response()
            ->json([
                'code' => 'FINISH',
                'message' => "İŞ kazası bildirgeleri başarılı bir şekilde çekildi!",
                'progress' => 100,
                'url' => route('declarations.incentives.is-kazasi')
            ]);
    }

    public function IsKazasiPost(Request $request)
    {
        $sgk_company = getSgkCompany();
        //dd($company);
        $captcha = $request->captcha;
        $result = sysloginIsKazasi($sgk_company, $captcha);
        if ($result) {
            return response()->json(['code' => 'LOGIN_OK', 'message' => 'Login Success']);
        } else {
            $cookieKey = startCookieIsKazasi($sgk_company);
            return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Fail', 'key' => $cookieKey]);
        }
    }

    public function calisanViziteleri()
    {
        session()->forget('excel_users');
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        $work_vizites = WorkVizite::where('sgk_company_id', $sgk_company->id)->orderBy('poliklinik_tarihi', 'DESC')->get();
        return view('reports.calisan_viziteleri', compact('sgk_company', 'work_vizites'));
    }
    public function IsVizitesiCek()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookieIsVizitesi);
        $getFirst = Curl::to('https://uyg.sgk.gov.tr/vizite/onayliRaporGiris.do')
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->get();

        $getFirst = botPageHtmlCleaner($getFirst);
        if (strstr($getFirst, "Sisteme Giris Yapiniz!")) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookieIsVizitesi' => null, 'cookieIsVizitesi_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'Çalışılmadığına Dair Bildirim Giriş Sistemi Giriş Hatası.',
                    'step' => 'calisan-viziteleri',
                ]);
        }
        if (strstr($getFirst, "isyeriSifresi")) {
            SgkCompany::where('id', $sgk_company->id)->update(['cookieIsVizitesi' => null, 'cookieIsVizitesi_status' => false]);
            return response()
                ->json([
                    'code' => 'LOGIN_FAIL',
                    'message' => 'Çalışılmadığına Dair Bildirim Giriş Sistemi Giriş Hatası.',
                    'step' => 'calisan-viziteleri',
                ]);
        }
        $dt = Carbon::today();
        $responseTarih = Curl::to('https://uyg.sgk.gov.tr/vizite/onayliRaporListele.do')
            ->withData([
                'tarih1' => '01.01.2018',
                'tarih2' => $dt->format('d.m.Y')
            ])
            ->allowRedirect()
            ->setCookieJar($cookieFile)
            ->setCookieFile($cookieFile)
            ->post();
        $responseTarih = botPageHtmlCleaner($responseTarih);
        preg_match_all('/<table width="100%" cellspacing="2" cellpadding="2" align="center" class="borderTable">(.*?)<\/table>/', $responseTarih, $responseTarihTr);


        if (isset($responseTarihTr[1])) {
            $onayli = $responseTarihTr[1];
            if(count($onayli) > 0) {
                preg_match_all('/<tr>(.*?)<\/tr>/', $onayli[0], $records);
                if (isset($records[1])) {
                    $sons = $records[1];

                    foreach ($sons as $son) {
                        preg_match_all('/<td class="labelsmall9" align="center">(.*?)<\/td>/', $son, $results);
                        $result = $results[1];
                        //dd(mb_convert_encoding($result[4], "UTF-8", "ISO-8859-9"));
                        $has = WorkVizite::where('sgk_company_id', $sgk_company->id)->where('tck', trim($result[3]))->where('poliklinik_tarihi', trim($result[6]))->first();
                        if (!$has) {
                            $saved = new WorkVizite();
                            $saved->sgk_company_id = $sgk_company->id;
                            $saved->takip_no = trim($result[1]);
                            $saved->rapor_sira_no = trim($result[2]);
                            $saved->tck = trim($result[3]);
                            $saved->ad_soyad = trim(mb_convert_encoding($result[4], "UTF-8", "ISO-8859-9"));
                            $saved->vaka = trim($result[5]);
                            $saved->poliklinik_tarihi = trim($result[6]);
                            $saved->isbasi_tarihi = trim($result[7]);
                            $saved->save();
                        }

                    }

                }
            }

        }

        SgkCompany::where('id', $sgk_company->id)->update(['cookieIsVizitesi' => null, 'cookieIsVizitesi_status' => false]);
        return response()
            ->json([
                'code' => 'FINISH',
                'message' => "Viziteler başarılı bir şekilde çekildi!",
                'progress' => 100,
                'url' => route('declarations.incentives.calisan-viziteleri')
            ]);
    }
    public function IsVizitesiPost(Request $request)
    {
        $sgk_company = getSgkCompany();
        //dd($company);
        $captcha = $request->captcha;
        $result = sysloginIsVizitesi($sgk_company, $captcha);
        if ($result) {
            return response()->json(['code' => 'LOGIN_OK', 'message' => 'Login Success']);
        } else {
            $cookieKey = startCookieIsVizitesi($sgk_company);
            return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Fail', 'key' => $cookieKey]);
        }
    }


    public function giriscikisBildirgesiStart()
    {
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();

        $infos = [];
        $last_date = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->first();

         if ($last_date) {
             $declarations = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->where('accrual', $last_date->accrual)->pluck('id');
             if (count($declarations) > 0) {
                 $declaration_services = IncentiveService::whereIn('approved_incentive_id', $declarations)->distinct('tck')->pluck('tck');
                 if (count($declaration_services) > 0) {
                     $infos = DeclarationInfo::whereIn('tck', $declaration_services)->get();
                 }
             }
         }

        return view('reports.giris_cikis_bildirgesi', compact('sgk_company', 'infos'));
    }

    public function GirisCikisBildirgesiCek()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookiegirisCikisBildirgesi);


        $last_date = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->first();
        $declarations = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->where('accrual', $last_date->accrual)->pluck('id');
        $incentive_services = IncentiveService::whereIn('approved_incentive_id', $declarations)->distinct('tck')->select('tck')->get();
        $total = count($incentive_services);

        $i = 0;

        foreach($incentive_services as $incentive_service) {
            $getFirst = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/amp/sigortaliTescilAction?jobid=sorgulama')
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->get();
            $response = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/amp/sigortaliTescilAction')

                ->withData([
                    'kimlikno' =>   $incentive_service->tck,
                    'jobid' => 'sorgula',
                    'tkrVno' => ''
                ])
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();

            $response = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/amp/sigortaliTescilAction')

                ->withData([
                    'kimlikno' => $incentive_service->tck,
                    'jobid' => 'reshowisegiris',
                    'tkrVno' => '0'
                ])
                ->allowRedirect()
                ->setCookieJar($cookieFile)
                ->setCookieFile($cookieFile)
                ->post();
            $getFirst = botPageHtmlCleaner($response);

            if (strstr($getFirst, "Tekrar Login olmak için") || strstr($getFirst, "E-Bildirge HATA")) {

                SgkCompany::where('id', $sgk_company->id)->update(['cookiegirisCikisBildirgesi' => null, 'cookiegirisCikisBildirgesi_status' => false]);
                return response()
                    ->json([
                        'code' => 'LOGIN_FAIL',
                        'message' => 'Giriş Çıkış Sistemi Giriş Hatası.',
                        'step' => 'giris_cikis',
                    ]);
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
                    $has_tck = DeclarationInfo::where('tck', $incentive_service->tck)->first();
                    if (!$has_tck) {
                        $save = new DeclarationInfo();
                        $save->tck = $incentive_service->tck;
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

    public function kimlikBildirimSistemi()
    {
        session()->forget('excel_users');
        session(["progress" => 1]);
        $sgk_company = getSgkCompany();
        $identity_notifications = IdentityNotification::where('sgk_company_id', $sgk_company->id)->orderBy('ise_giris_tarihi', 'DESC')->get();
        return view('reports.kimlik_bildirim', compact('sgk_company', 'identity_notifications'));
    }
    public function KbsCalisanCek()
    {

        $sgk_company = getSgkCompany();
        $cookieFile = cookieFileName($sgk_company->cookieKbsCalisan);


        $cookieKey = $sgk_company->cookieKbsCalisan;
        $response = Curl::to('https://kbscalisan.egm.gov.tr/Proje/calisansorgusu.aspx')
            ->withData([
                'txtilktarih' => '01.01.1900',
                'txtsontarih' => Carbon::now()->format('d.m.Y'),
                '__EVENTTARGET' => 'btnExcel',
                '__EVENTARGUMENT' => '',
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();
        setlocale(LC_ALL, 'tr_TR');
        $text =  iconv("ISO-8859-1", "UTF-8", botPageHtmlCleaner($response));
        $text = str_replace('Ý', 'İ', $text);
        $text = str_replace('Þ', 'Ş', $text);
        $text = str_replace('Â', '', $text);
        $re = '/<tr>.*?<\/tr>/m';
        preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);
        $k = 0;
        $rows = [];
        foreach($matches as $key => $value) {
            if ($k > 0) {
                $satir = $value[0];
                $satir = str_replace('<tr>', '', $satir);
                $satir = str_replace('</tr>', '', $satir);
                preg_match_all('/<td>(.*?)<\/td>/m', $satir, $matche_rows, PREG_SET_ORDER, 0);
                //dd($matche_rows);
                $rows[$k-1]['ise_giris_tarihi'] = $matche_rows[3][1];
                $rows[$k-1]['isten_ayrilis_tarihi'] = $matche_rows[4][1];
                $rows[$k-1]['tck'] = $matche_rows[7][1];

            }
           $k++;
        }

        foreach ($rows as $key => $value) {
            $has = IdentityNotification::where('sgk_company_id', $sgk_company->id)->where('tck', $value['tck'])->first();

            $giris = explode('/', $value['ise_giris_tarihi']);
            if (count($giris) == 3) {
                $ise_giris_tarihi = $giris[2].'-'.$giris[1].'-'.$giris[0];
            } else {
                $ise_giris_tarihi = null;
            }

            $cikis = explode('/', $value['isten_ayrilis_tarihi']);

            if (count($cikis) == 3) {
                $isten_ayrilis_tarihi = $cikis[2].'-'.$cikis[1].'-'.$cikis[0];
            } else {
                $isten_ayrilis_tarihi = null;
            }

            if (!$has) {
                $save = new IdentityNotification();
                $save->sgk_company_id = $sgk_company->id;
                $save->tck = $value['tck'];
                $save->ise_giris_tarihi = $ise_giris_tarihi;
                $save->isten_ayrilis_tarihi = $isten_ayrilis_tarihi;
                $save->save();
            } else {
                $has->update([
                    'isten_ayrilis_tarihi' => $cikis[2].'-'.$cikis[1].'-'.$cikis[0],
                    'ise_giris_tarihi' => $giris[2].'-'.$giris[1].'-'.$giris[0],
                ]);
            }

        }



        SgkCompany::where('id', $sgk_company->id)->update(['cookieKbsCalisan' => null, 'cookieKbsCalisan_status' => false]);
        return response()
            ->json([
                'code' => 'FINISH',
                'message' => "Kimlik bildirim sistemi verileri  başarılı bir şekilde çekildi!",
                'progress' => 100,
                'url' => route('declarations.incentives.kimlik-bildirim-sistemi')
            ]);
    }


    public function KbsCalisanPost(Request $request)
    {
        $sgk_company = getSgkCompany();
        //dd($company);
        $captcha = $request->captcha;
        $result = sysloginKbsCalisan($sgk_company, $captcha);
        if ($result) {
            return response()->json(['code' => 'LOGIN_OK', 'message' => 'Login Success']);
        } else {
            $cookieKey = startCookieKbsCalisan($sgk_company);
            return response()->json(['code' => 'LOGIN_FAIL', 'message' => 'Login Fail', 'key' => $cookieKey]);
        }
    }

}
