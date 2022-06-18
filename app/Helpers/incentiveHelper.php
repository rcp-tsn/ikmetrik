<?php

use App\Models\DeclarationService;
use App\Models\IncentiveService;
use App\Models\Incentive;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
if (!function_exists('cw_enerji')) {

    function cw_enerji($incitements,$multipleLaw)
    {
        foreach ($incitements as  $laws)
        {

            foreach ($laws as $incentive)
            {
                if (count($multipleLaw[$incentive["tck"]]) !=1)
                {
                    $datas[$incentive["tck"]][] = $incentive;
                }
            }
        }
        if (isset($datas))
        {
            foreach ($datas as $key =>  $data)
            {
                $deger = [];
                $max_deger = [];

                $count = count($datas[$key]);
                $count = $count - 1;
                for ($a = 0; $a <=$count;$a++)
                {

                    $deger[] = $data[$a]['tesvik'];

                }

                $max_deger[$data[0]['tck']] = max($deger);


                foreach ($incitements as $value=>  $laws)
                {
                    foreach ($laws as $b => $incentive)
                    {
                        $key = [];
                        $key = array_keys($max_deger);
                        $key = $key[0];

                        if ($incentive["tck"] == $key)
                        {
                            if ($incentive['tesvik'] != $max_deger[$key])
                            {

                                unset($incitements[$value][$b]);

                            }


                        }
                    }
                }

            }
        }


        return $incitements;
    }

}

if (!function_exists('enDusukKazanc')) {
    function enDusukKazanc($incitements,$personel)
    {
        $data = [];
        $enDusukKazanc = 1000000;
        $keys = 0;

        foreach ($incitements as $key =>  $incitement) {

                    if ($incitement['tesvik'] < $enDusukKazanc and $personel['min_personel'] ==  $incitement['min_personel'] and  $incitement['law'] == $personel['law']) {
                        $enDusukKazanc = $incitement['tesvik'];
                        $keys = $key;
                        $kazanc = $incitement['tesvik'];
                    }
        }

        return $data = array('0' => $keys,'1' => $kazanc);
    }

}



if (!function_exists('cw_enerji2'))
{
    function cw_enerji2($incitements,$multipleLaw,$notDaysInfo)
    {

        foreach ($incitements as  $laws)
        {
            foreach ($laws as $incentive)
            {

                if (isset($multipleLaw[$incentive["tck"]])) {
                    if (count($multipleLaw[$incentive["tck"]]) != 1) {
                        $datas[$incentive["tck"]][] = $incentive;
                    }
                }
            }
        }
        if (isset($datas))
        {

            foreach ($datas as $key =>  $data)
            {

                $deger = [];
                $max_deger = [];

                $count = count($datas[$key]);
                $count = $count - 1;
                for ($a = 0; $a <=$count;$a++)
                {

                    $deger[] = $data[$a]['tesvik'];

                }

                $max_deger[$data[0]['tck']] = max($deger);


                foreach ($incitements as $value =>  $laws)
                {
                    foreach ($laws as $b => $incentive)
                    {
                        $key = [];
                        $key = array_keys($max_deger);
                        $key = $key[0];

                        if ($incentive["tck"] == $key)
                        {
                            if ($incentive['tesvik'] != $max_deger[$key])
                            {

                                unset($incitements[$value][$b]);

                                if (isset($notDaysInfo[$value][$incentive['min_personel']]))
                                {
                                   // $incitements[$value][$b] =
                                    $data = [];
                                    $enBuyukKazanc = 0;
                                    $keys = 0;

                                        foreach ($notDaysInfo[$value][$incentive['min_personel']] as $tc => $personel)
                                        {


                                            if ($personel['tesvik'] > $enBuyukKazanc and $personel['min_personel'] ==  $incentive['min_personel'] and  $personel['law'] == $value) {

                                                $enBuyukKazanc = $personel['tesvik'];
                                                $keys = $tc;
                                                $kazanc = $personel['tesvik'];
                                            }
                                        }
                                        if ($keys > 0)
                                        {
                                            $incitements[$value][$b] = $notDaysInfo[$value][$incentive['min_personel']][$keys];

                                            unset($notDaysInfo[$value][$incentive['min_personel']][$keys]);
                                        }


                                }
                            }


                        }
                    }
                }

            }
        }


        return $values = array('incitements'=>$incitements,'notDaysInfo'=>$notDaysInfo);
    }

}






if (!function_exists('cw_enerji_incentives_finish'))
{
    function cw_enerji_incentives_finish($incitements,$date,$company)
    {
        $date = explode('-',$date);
        $a = $date['1'] - 1;
        $date = ['0'=>$date['0'],$a,$date['2']];
        $date = implode('-',$date);

        $incentive_services = IncentiveService::where('sgk_company_id',$company['id'])
            ->where('accrual',$date)
            ->get();


        foreach ($incentive_services as $services)
        {
            $law = \App\Models\ApprovedIncentive::find($services->approved_incentive_id);
            foreach ($incitements as $incentives) {
                foreach ($incentives as $law => $incenti) {
                    if ($services->tck == $incenti['tck']) {
                        // Hakedişi Devam Edenler
                        $nows[$incenti['tck']] = $incenti;
                    }
                }

            }



        }


        foreach ($incentive_services as $key => $services) {

            if ($key > 0) {
                $incentive = Incentive::where('sgk_company_id',$company['id'])
                    ->where('tck',$services->tck)
                    ->where('job_finish','<=',$date)
                    ->first();

                $law = \App\Models\ApprovedIncentive::find($services->approved_incentive_id);
                if (isset($nows))
                {
                    //İşten Çıkanları Rapora Basmamak İçin Yazdım
                    $date = Carbon::now()->startofMonth()->subMonth(2)->endOfMonth()->toDateString();

                    if (!array_key_exists($services->tck, $nows) and $law->law_no != 5510 and !$incentive ) {
                        if ($services->tck != 0) {
                            if ($law->law_no != 0 and $law->law_no != 14857 and $law->law_no != 5746) {
                                $finish_incentives[] = ['0' => $services->tck, '1' => $services->isim, '2' => $services->soyisim, '3' => $law->law_no];

                            }
                        }

                    }
                }
                else
                {
                    if ($law->law_no != 5510 and $law->law_no != 0 and $law->law_no != 14857 and $law->law_no != 5746  and !$incentive)
                    {
                        $finish_incentives[] = ['0' => $services->tck, '1' => $services->isim, '2' => $services->soyisim, '3' => $law->law_no];

                    }
                }

            }
        }


        if (isset($finish_incentives))
        {
            return $finish_incentives;
        }
        else
        {
            return  $finish_incentives = [];
        }


    }
}
if (!function_exists('old_law'))
{
    function old_law($tck,$sgk_company_id,$date)
    {

        $date = explode('-',$date);
        $a = $date['1'] - 1;
        $date = ['0'=>$date['0'],$a,$date['2']];
        $date = implode('-',$date);
        $incentive_service = IncentiveService::where('tck',$tck)
            ->where('sgk_company_id',$sgk_company_id)
            ->where('accrual',$date)
            ->first();
        if (!empty($incentive_service->approved_incentive_id))
        {
            $law = \App\Models\ApprovedIncentive::find($incentive_service->approved_incentive_id);
            return $law->law_no;
        }
        else
        {
            $law = '-';
            return $law;
        }



    }
}
if (!function_exists('cookieFileName')) {
    function cookieFileName($uuid)
    {
        return storage_path('cookies/' . $uuid . '.txt');
    }
}

if (!function_exists('startCookieV3')) {
    function startCookieV3($sgk_company, $existingCookieKey = null)
    {

        $cookieKey = $existingCookieKey ?? \Ramsey\Uuid\Uuid::uuid4()->toString();
        $captcha = Ixudra\Curl\Facades\Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/PG')
            ->withHeader('Referer: https://uyg.sgk.gov.tr/IsverenSistemi/PG')
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->get();
//        $headers = array();
//        $options = array();
//        $request = Requests::get('https://uyg.sgk.gov.tr/IsverenSistemi/PG', $headers, $options);

        cache(['captcha.' . $cookieKey => $captcha], 1440 * 60);
        $sgk_company->cookiev3 = $cookieKey;
        $sgk_company->cookiev3_status = 0;
        $sgk_company->save();
        return $cookieKey;
    }
}

if (!function_exists('startCookieV2')) {
    function startCookieV2($sgk_company, $existingCookieKey = null)
    {

        $cookieKey = $existingCookieKey ?? \Ramsey\Uuid\Uuid::uuid4()->toString();

        $captcha = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/PG')
            ->withHeader('Referer: https://ebildirge.sgk.gov.tr/EBildirgeV2')
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->get();

        cache(['captcha.' . $cookieKey => $captcha], 1440 * 60);
        $sgk_company->cookiev2 = $cookieKey;
        $sgk_company->cookiev2_status = 0;
        $sgk_company->save();
        return $cookieKey;
    }
}


if (!function_exists('sysloginv2')) {
    function sysloginv2($sgk_company, $captcha)
    {
        $cookieKey = $sgk_company->cookiev2;
        $response = Curl::to('https://ebildirge.sgk.gov.tr/EBildirgeV2/login/kullaniciIlkKontrollerGiris.action')
            ->withData([
                'username' => $sgk_company->company_username,
                'isyeri_kod' => $sgk_company->company_usercode,
                'password' => $sgk_company->company_syspassword,
                'isyeri_sifre' => $sgk_company->company_password,
                'isyeri_guvenlik' => $captcha
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();
        if (strpos($response, "Onaylanmış Belgeler")) {
            $sgk_company->cookiev2_status = 1;
            $sgk_company->save();
            return true;
        } else {

            $sgk_company->cookiev2_status = 0;
            $sgk_company->save();
            return false;


        }
    }
}


if (!function_exists('sysloginv3')) {
    function sysloginv3($sgk_company, $captcha)
    {
        if (!$sgk_company->cookiev3) {
            return false;
        }
        $cookieKey = $sgk_company->cookiev3;
        //dd($cookieKey);
        $response = Curl::to('https://uyg.sgk.gov.tr/IsverenSistemi/login/kullaniciIlkKontrollerGiris.action')
            ->withData([
                'username' => $sgk_company->company_username,
                'isyeri_kod' => $sgk_company->company_usercode,
                'password' => $sgk_company->company_syspassword,
                'isyeri_sifre' => $sgk_company->company_password,
                'isyeri_guvenlik' => $captcha
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();
        $sgk_company->cookiev3_status = true;
        $sgk_company->save();
        return true;
    }
}


if (!function_exists('getCaptcha')) {
    function getCaptcha($uuid)
    {
        $captchaData = cache('captcha.' . $uuid, null);
        return response($captchaData)
            ->withHeaders([
                'Content-Type' => "image/png"
            ]);
    }
}

if (!function_exists('tesvikHesapla')) {
    function tesvikHesapla($kanun, $maas, $ikramiye = 0, $gun = 1, $return_type = false)
    {
        if ($gun == 0) {
            if ($return_type) {
                return 0;
            }
            return "0";
        }

        $return = 0;
        $date = date("Y");

        $maas = str_replace(",", ".", $maas);
        $ikramiye = str_replace(",", ".", $ikramiye);

        if (!is_numeric($ikramiye)) {
            $ikramiye = 0;
        }
        if (!is_numeric($maas)) {
            $maas = 0;
        }
        $gelir = $maas + $ikramiye;

        if (($date == 2019 or $date == 2020 or $date == 2021) and $kanun == 6111) {
            $g_6111 = $gelir * 0.155;
            $return = $g_6111;

        } elseif (($date == 2019 or $date == 2020 or $date == 2021) and $kanun == 17103) {

            $g_17103 = $gelir * 0.375;
            if ($g_17103 > 3577.50) {
                $return = 3577.50;
            } else {
                $return = $g_17103;
            }

        } elseif (($date == 2019 or $date == 2020 or $date == 2021) and $kanun == 7103) {

            $g_17103 = $gelir * 0.375;
            if ($g_17103 > 3577.50) {
                $return = 3577.50;
            } else {
                $return = $g_17103;
            }

        } elseif (($date == 2019 or $date == 2020 or $date == 2021) and $kanun == 27103) {
            $g_27103 = $gelir * 0.375;
            if ($g_27103 > 1341.56) {
                $return = 1341.56;
            } else {
                $return = $g_27103;
            }
        } elseif ($kanun == 27256) {
            $return = $gun * 53.67;
        } elseif ($kanun == 7252) {
            $return = $gun * 44.72;
        }
        elseif ($kanun == 3294)
        {
            $return = ($gelir * 15.50)/100;
            if ($return > 1896.08)
            {
                $return = 1896.08;
            }

        }
        elseif ($kanun == 7319)
        {
            $return = ($gelir * 37.50)/100;
            $maas = ($gelir * 5)/100;
            $return = $return - $maas;
            if ($return > 1341.56)
            {
                $return = 1341.56;
            }

        }

        if ($return == 0) {
            if ($return_type) {
                return 0;
            }
            return "-";
        }
        if ($return_type) {
            return round($return, 2);
        }
        return round($return, 2) . "";
    }
}

function notDaysInfo($personals = [])
{
    $personelDays = [];


    foreach ($personals as $dayKey => $prs) {
        foreach ($prs as $prs2) {
            if (isset($prs2['law'])) {
                if ($prs2['law'] != 7252) {
                    if (!isset($prs2['declaration_service']['isim'])) {
                        $prs2['declaration_service'] = $prs2['declaration_service'][0];
                    }
                    $law_tck [$prs2['tck']] = $prs2;
                    // List type edit start
                    $lawPersonel = [];
                    $lawPersonel['sgk_company_id'] = $prs2['sgk_company_id'];
                    $lawPersonel['document_type'] = $prs2['declaration_service']['declaration'][0]['document_type'];
                    $lawPersonel['name'] = $prs2['declaration_service']['isim'];
                    $lawPersonel['surname'] = $prs2['declaration_service']['soyisim'];
                    $lawPersonel['tck'] = $prs2['tck'];
                    $lawPersonel['old_law'] = $prs2['declaration_service']['declaration'][0]['law'];
                    $lawPersonel['law'] = $prs2['law'];
                    if ($lawPersonel['old_law'] == $lawPersonel['law']) {
                        $lawPersonel['law_different'] = false;
                    } else {
                        $lawPersonel['law_different'] = true;
                    }
                    $lawPersonel['start'] = $prs2['start'];
                    $lawPersonel['finish'] = $prs2['finish'];
                    $lawPersonel['job_start'] = $prs2['job_start'];
                    $lawPersonel['job_finish'] = $prs2['job_finish'];
                    $lawPersonel['min_personel'] = $prs2['min_personel'];
                    if ($prs2['law'] == 7252) {
                        $lawPersonel['days'] = ($prs2['days'] == 9999) ? "- " : $prs2['days'];
                    } else {
                        $lawPersonel['days'] = $prs2['declaration_service']['gun'];
                    }
                    $lawPersonel['ucret'] = $prs2['declaration_service']['ucret_tl'];
                    $lawPersonel['ikramiye'] = $prs2['declaration_service']['ikramiye_tl'];
                    if ($prs2['law'] == 7252) {
                        $lawPersonel['tesvik'] = tesvikHesapla($prs2['law'], $prs2['declaration_service']['ucret_tl'], $prs2['declaration_service']['ikramiye_tl'], $prs2['days']);
                    } else {
                        $lawPersonel['tesvik'] = tesvikHesapla($prs2['law'], $prs2['declaration_service']['ucret_tl'], $prs2['declaration_service']['ikramiye_tl'], $prs2['declaration_service']['gun']);
                    }

                    foreach ($prs2['declaration_service']['declaration'] as $declrtn) {
                        $personelDays[$dayKey][$prs2['law']][$prs2['min_personel']][$prs2['tck']][$declrtn['document_type']] = $lawPersonel;
                    }
                }

            } else {
                foreach ($prs2 as $key => $personel) {
                    if ($personel['law'] != 7252) {
                        $law_tck [$personel['tck']] = $personel;
                        if (!isset($personel['declaration_service']['isim'])) {

                            $personel['declaration_service'] = $personel['declaration_service'][0];
                        }
                        $lawPersonel = [];
                        $lawPersonel['sgk_company_id'] = $personel['sgk_company_id'];
                        $lawPersonel['document_type'] = $personel['declaration_service']['declaration'][0]['document_type'];
                        $lawPersonel['old_law'] = $personel['declaration_service']['declaration'][0]['law'];
                        $lawPersonel['name'] = $personel['declaration_service']['isim'];
                        $lawPersonel['surname'] = $personel['declaration_service']['soyisim'];
                        $lawPersonel['tck'] = $personel['tck'];
                        $lawPersonel['law'] = $personel['law'];
                        if ($lawPersonel['old_law'] == $lawPersonel['law']) {
                            $lawPersonel['law_different'] = false;
                        } else {
                            $lawPersonel['law_different'] = true;
                        }
                        $lawPersonel['start'] = $personel['start'];
                        $lawPersonel['finish'] = $personel['finish'];
                        $lawPersonel['job_start'] = $personel['job_start'];
                        $lawPersonel['job_finish'] = $personel['job_finish'];
                        $lawPersonel['min_personel'] = $personel['min_personel'];
                        if ($personel['law'] == 7252) {
                            $lawPersonel['days'] = ($personel['days'] == 9999) ? "- " : $personel['days'];
                        } else {
                            $lawPersonel['days'] = $personel['declaration_service']['gun'];
                        }
                        $lawPersonel['ucret'] = $personel['declaration_service']['ucret_tl'];
                        $lawPersonel['ikramiye'] = $personel['declaration_service']['ikramiye_tl'];
                        if ($personel['law'] == 7252) {
                            $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['declaration_service']['ucret_tl'], $personel['declaration_service']['ikramiye_tl'], $personel['days']);
                        } else {
                            $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['declaration_service']['ucret_tl'], $personel['declaration_service']['ikramiye_tl'], $personel['declaration_service']['gun']);
                        }


                        foreach ($personel['declaration_service']['declaration'] as $declrtn) {
                            $personelDays[$dayKey][$personel['law']][$personel['min_personel']][$personel['tck']][$declrtn['document_type']] = $lawPersonel;

                        }
                    }

                }
            }
        }
    }

    return $personelDays;

}


//Kayıp Kaçak İçin Yaptım

function notDaysInfo2($personals = [])
{
    $personelDays = [];


    foreach ($personals as $dayKey => $prs) {
        foreach ($prs as $prs2) {

            if (isset($prs2['law'])) {
                if ($prs2['law'] != 7252) {
                    if (!isset($prs2['incentive_service']['isim'])) {
                        $prs2['incentive_service'] = $prs2['incentive_service'][0];
                    }
                    $law_tck [$prs2['tck']] = $prs2;
                    // List type edit start
                    $lawPersonel = [];
                    $lawPersonel['sgk_company_id'] = $prs2['sgk_company_id'];
                    $lawPersonel['document_type'] = $prs2['incentive_service']['leak_approvied_incentive'][0]['document_type'];
                    $lawPersonel['name'] = $prs2['incentive_service']['isim'];
                    $lawPersonel['surname'] = $prs2['incentive_service']['soyisim'];
                    $lawPersonel['tck'] = $prs2['tck'];

                    $lawPersonel['old_law'] = $prs2['incentive_service']['leak_approvied_incentive'][0]['law_no'];
                    $lawPersonel['law'] = $prs2['law'];
                    if ($lawPersonel['old_law'] == $lawPersonel['law']) {
                        $lawPersonel['law_different'] = false;
                    } else {
                        $lawPersonel['law_different'] = true;
                    }
                    $lawPersonel['start'] = $prs2['start'];
                    $lawPersonel['finish'] = $prs2['finish'];
                    $lawPersonel['job_start'] = $prs2['job_start'];
                    $lawPersonel['job_finish'] = $prs2['job_finish'];
                    $lawPersonel['min_personel'] = $prs2['min_personel'];
                    if ($prs2['law'] == 7252) {
                        $lawPersonel['days'] = ($prs2['days'] == 9999) ? "- " : $prs2['days'];
                    } else {
                        $lawPersonel['days'] = $prs2['incentive_service']['gun'];
                    }
                    $lawPersonel['ucret'] = $prs2['incentive_service']['ucret_tl'];
                    $lawPersonel['ikramiye'] = $prs2['incentive_service']['ikramiye_tl'];
                    if ($prs2['law'] == 7252) {
                        $lawPersonel['tesvik'] = tesvikHesapla($prs2['law'], $prs2['incentive_service']['ucret_tl'], $prs2['incentive_service']['ikramiye_tl'], $prs2['days']);
                    } else {
                        $lawPersonel['tesvik'] = tesvikHesapla($prs2['law'], $prs2['incentive_service']['ucret_tl'], $prs2['incentive_service']['ikramiye_tl'], $prs2['incentive_service']['gun']);
                    }

                    foreach ($prs2['incentive_service']['leak_approvied_incentive'] as $declrtn) {
                        $personelDays[$dayKey][$prs2['law']][$prs2['min_personel']][$prs2['tck']] = $lawPersonel;
                    }
                }

            } else {
                foreach ($prs2 as $key => $personel) {
                    if ($personel['law'] != 7252) {
                        $law_tck [$personel['tck']] = $personel;
                        if (!isset($personel['incentive_service']['isim'])) {

                            $personel['incentive_service'] = $personel['incentive_service'][0];
                        }
                        $lawPersonel = [];
                        $lawPersonel['sgk_company_id'] = $personel['sgk_company_id'];
                        $lawPersonel['document_type'] = $personel['incentive_service']['leak_approvied_incentive'][0]['document_type'];
                        $lawPersonel['old_law'] = $personel['incentive_service']['leak_approvied_incentive'][0]['law_no'];
                        $lawPersonel['name'] = $personel['incentive_service']['isim'];
                        $lawPersonel['surname'] = $personel['incentive_service']['soyisim'];
                        $lawPersonel['tck'] = $personel['tck'];
                        $lawPersonel['law'] = $personel['law'];
                        if ($lawPersonel['old_law'] == $lawPersonel['law']) {
                            $lawPersonel['law_different'] = false;
                        } else {
                            $lawPersonel['law_different'] = true;
                        }
                        $lawPersonel['start'] = $personel['start'];
                        $lawPersonel['finish'] = $personel['finish'];
                        $lawPersonel['job_start'] = $personel['job_start'];
                        $lawPersonel['job_finish'] = $personel['job_finish'];
                        $lawPersonel['min_personel'] = $personel['min_personel'];
                        if ($personel['law'] == 7252) {
                            $lawPersonel['days'] = ($personel['days'] == 9999) ? "- " : $personel['days'];
                        } else {
                            $lawPersonel['days'] = $personel['incentive_service']['gun'];
                        }
                        $lawPersonel['ucret'] = $personel['incentive_service']['ucret_tl'];
                        $lawPersonel['ikramiye'] = $personel['incentive_service']['ikramiye_tl'];
                        if ($personel['law'] == 7252) {
                            $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['incentive_service']['ucret_tl'], $personel['incentive_service']['ikramiye_tl'], $personel['days']);
                        } else {
                            $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['incentive_service']['ucret_tl'], $personel['incentive_service']['ikramiye_tl'], $personel['incentive_service']['gun']);
                        }


                        foreach ($personel['incentive_service']['leak_approvied_incentive'] as $declrtn) {
                            $personelDays[$dayKey][$personel['law']][$personel['min_personel']][$personel['tck']] = $lawPersonel;

                        }
                    }

                }
            }
        }
    }

    return $personelDays;

}



function ConvertToUTF8($text)
{
    $text = utf8_encode($text);
    $encoding = mb_detect_encoding($text, mb_detect_order(), false);
    $text = iconv($encoding, 'UTF-8', $text);
    $find = array("Ä", "Å", "Ä°", "Ã", "Ã", "Ã");
    $replace = array("Ğ", "Ş", "İ", "Ü", "Ö", "Ç");
    $text = str_replace($find, $replace, $text);
    return $text;
}

function decimalFormat($price, $excel = false)
{
    if ($price == "") {
        return 0;
    }
    $price = str_replace(".", "", $price);
    $price = str_replace(",", ".", $price);
    $price = trim($price);
    return $price;
}

function decimalFormatExcel($price, $excel = false)
{
    if ($price == "") {
        return 0;
    }
    $price = str_replace(",", ".", $price);
    $price = trim($price);
    return $price;
}

function botPageHtmlCleaner($html, $filter = false)
{
    $html = html_entity_decode($html);
    $html = str_replace("\t", "", $html);
    $html = str_replace("\r", "", $html);
    $html = str_replace("\n", "", $html);
    $html = str_replace("    ", "", $html);
    if ($filter) {
        foreach ($filter as $filter_clean) {
            $html = str_replace($filter_clean, "", $html);
        }
    }
    return $html;
}

function captchaSolver($cookie)
{
    $fileName = $cookie . ".png";
    $image = storage_path('app' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR . $fileName);
    $id = Curl::to('http://azcaptcha.com/in.php')
        ->withContentType('multipart/form-data;')
        ->withData(['key' => env('CAPCHA_APIKEY'), 'method' => 'post'])
        ->withFile('file', $image)
        ->post();

    if (strstr($id, "OK")) {
        $id = explode("|", $id)[1];
    } else {
        return false;
    }
    sleep(3);
    $solver = Curl::to('https://azcaptcha.com/res.php')
        ->withData([
            'key' => env('CAPCHA_APIKEY'),
            'action' => 'get',
            'id' => $id
        ])
        ->get();
    if (strstr($solver, "OK")) {
        return mb_strtoupper(trim(explode("|", $solver)[1]));
    } else {
        return false;
    }

}


//iş kazası


if (!function_exists('startCookieIsKazasi')) {
    function startCookieIsKazasi($sgk_company, $existingCookieKey = null)
    {
        $cookieKey = $existingCookieKey ?? \Ramsey\Uuid\Uuid::uuid4()->toString();
        $captcha = Curl::to('https://kesenek.sgk.gov.tr/KesenekWeb/simpleCaptcha.png')
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->get();
        cache(['captcha.' . $cookieKey => $captcha], 1440 * 60);
        $sgk_company->cookieIsKazasi = $cookieKey;
        $sgk_company->cookieIsKazasi_status = 0;
        $sgk_company->save();
        return $cookieKey;
    }
}

if (!function_exists('sysloginIsKazasi')) {
    function sysloginIsKazasi($sgk_company, $captcha)
    {

        $cookieKey = $sgk_company->cookieIsKazasi;
        $response = Curl::to('https://kesenek.sgk.gov.tr/IsvBildirimFormu/kullanici_login.do')
            ->withData([
                'kullaniciAdi' => $sgk_company->company_username,
                'isyeriKodu' => $sgk_company->company_usercode,
                'isyeriSifresi' => $sgk_company->company_password,
                'guvenlikKodu' => $captcha
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();

        $response = Curl::to('https://kesenek.sgk.gov.tr/IsvBildirimFormu/kullanici_login.do')
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->get();
        $response = Curl::to('https://kesenek.sgk.gov.tr/IsvBildirimFormu/kullanici_login.do')
            ->withData([
                'kullaniciAdi' => $sgk_company->company_username,
                'isyeriKodu' => $sgk_company->company_usercode,
                'isyeriSifresi' => $sgk_company->company_password,
                'guvenlikKodu' => $captcha
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();
        if (strstr($response, "guvenlikKodu")) {
            $sgk_company->cookieIsKazasi_status = 0;
            $sgk_company->save();
            return false;
        } else {
            $sgk_company->cookieIsKazasi_status = 1;
            $sgk_company->save();
            return true;
        }

    }
}


//işe giriş çıkış bildirgesi


if (!function_exists('startCookieGirisCikisBildirgesi')) {
    function startCookieGirisCikisBildirgesi($sgk_company, $existingCookieKey = null)
    {
        $cookieKey = $existingCookieKey ?? \Ramsey\Uuid\Uuid::uuid4()->toString();
        $captcha = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/PG')
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->get();
        cache(['captcha.' . $cookieKey => $captcha], 1440 * 60);
        $sgk_company->cookiegirisCikisBildirgesi = $cookieKey;
        $sgk_company->cookiegirisCikisBildirgesi_status = 0;
        $sgk_company->save();
        return $cookieKey;
    }
}

if (!function_exists('sysloginGirisCikisBildirgesi')) {
    function sysloginGirisCikisBildirgesi($sgk_company, $captcha)
    {

        $cookieKey = $sgk_company->cookiegirisCikisBildirgesi;
        $response = Curl::to('https://uyg.sgk.gov.tr/SigortaliTescil/amp/loginldap')
            ->withData([
                'j_username' => $sgk_company->company_username,
                'isyeri_kod' => $sgk_company->company_usercode,
                'j_password' => $sgk_company->company_syspassword,
                'isyeri_sifre' => $sgk_company->company_password,
                'isyeri_guvenlik' => $captcha
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();

        if (strpos($response, "j_username") && strpos($response, "j_password")) {
            $sgk_company->cookiegirisCikisBildirgesi_status = 0;
            $sgk_company->save();
            return false;
        } else {
            $sgk_company->cookiegirisCikisBildirgesi_status = 1;
            $sgk_company->save();
            return true;
        }

    }
}
//iş vizitesi

if (!function_exists('startCookieIsVizitesi')) {
    function startCookieIsVizitesi($sgk_company, $existingCookieKey = null)
    {
        $cookieKey = $existingCookieKey ?? \Ramsey\Uuid\Uuid::uuid4()->toString();
        $captcha = Curl::to('https://uyg.sgk.gov.tr/vizite/Captcha.jpg')
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->get();
        cache(['captcha.' . $cookieKey => $captcha], 1440 * 60);
        $sgk_company->cookieIsVizitesi = $cookieKey;
        $sgk_company->cookieIsVizitesi_status = 0;
        $sgk_company->save();
        return $cookieKey;
    }
}

if (!function_exists('sysloginIsVizitesi')) {
    function sysloginIsVizitesi($sgk_company, $captcha)
    {

        $cookieKey = $sgk_company->cookieIsVizitesi;
        $response = Curl::to('https://uyg.sgk.gov.tr/vizite/kullanici_login.do')
            ->withData([
                'kullaniciAdi' => $sgk_company->company_username,
                'isyeriKodu' => $sgk_company->company_usercode,
                'isyeriSifresi' => $sgk_company->company_password,
                'guvenlikKodu' => $captcha
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();


        if (strstr($response, "guvenlikKodu")) {
            $sgk_company->cookieIsVizitesi_status = 0;
            $sgk_company->save();
            return false;
        } else {
            $sgk_company->cookieIsVizitesi_status = 1;
            $sgk_company->save();
            return true;
        }

    }
}


//kbs çalışan

if (!function_exists('startCookieKbsCalisan')) {
    function startCookieKbsCalisan($sgk_company, $existingCookieKey = null)
    {
        $cookieKey = $existingCookieKey ?? \Ramsey\Uuid\Uuid::uuid4()->toString();
        $captcha = Curl::to('https://kbscalisan.egm.gov.tr/RetCap.aspx')
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->get();
        cache(['captcha.' . $cookieKey => $captcha], 1440 * 60);
        $sgk_company->cookieKbsCalisan = $cookieKey;
        $sgk_company->cookieKbsCalisan_status = 0;
        $sgk_company->save();
        return $cookieKey;
    }
}

if (!function_exists('sysloginKbsCalisan')) {
    function sysloginKbsCalisan($sgk_company, $captcha)
    {

        $cookieKey = $sgk_company->cookieKbsCalisan;
        $response = Curl::to('https://kbscalisan.egm.gov.tr/')
            ->withData([
                'txtkullaniciadi' => $sgk_company->kbcalisan_email,
                'txtsifre' => $sgk_company->kbcalisan_sifre,
                '__EVENTTARGET' => 'Button1',
                '__EVENTARGUMENT' => '',
                'txtCap' => $captcha,
                'AntiForgeryToken' => '',
                'GuidId' => '',
            ])
            ->allowRedirect()
            ->setCookieJar(cookieFileName($cookieKey))
            ->setCookieFile(cookieFileName($cookieKey))
            ->post();


        if (strstr($response, "guvenlikKodu")) {
            $sgk_company->cookieKbsCalisan_status = 0;
            $sgk_company->save();
            return false;
        } else {
            $sgk_company->cookieKbsCalisan_status = 1;
            $sgk_company->save();
            return true;
        }

    }
}

if (!function_exists('getEmployeeJobStart')) {
    function getEmployeeJobStart($incentive, $approved_ids, $declaration_ids)
    {

        $start_control = DeclarationService::whereIn('declaration_id', $declaration_ids)->where('tck', $incentive->tck)->orderBy('job_start', 'DESC')->whereNotNull('job_start')->select('job_start')->first();
        if ($start_control) {
            $job_start_date = $start_control->job_start;
        } else {
            $start_control = IncentiveService::whereIn('approved_incentive_id', $approved_ids)->where('tck', $incentive->tck)->orderBy('job_start', 'DESC')->whereNotNull('job_start')->select('job_start')->first();
            if ($start_control) {
                $job_start_date = $start_control->job_start;
            }
        }

        if (isset($job_start_date)) {
            return $job_start_date;
        } else {
            die($incentive->tck . ' için job start alınamadı!');
        }


    }
}

if (!function_exists('getEmployeeJobFinish')) {
    function getEmployeeJobFinish($incentive, $approved_ids, $declaration_ids)
    {

        $finish_control = IncentiveService::whereIn('approved_incentive_id', $approved_ids)->where('tck', $incentive->tck)->orderBy('job_finish', 'DESC')->whereNotNull('job_finish')->select('job_finish')->first();
        if ($finish_control) {
            $job_finish_date = $finish_control->job_finish;
        }

        if (isset($job_finish_date)) {
            return $job_finish_date;
        } else {
            return null;
        }


    }
}

if (!function_exists('getBeforeLaw')) {
    function getBeforeLaw($sgk_company_id, $tck, $law)
    {

        $q = IncentiveService::where('sgk_company_id', $sgk_company_id)->where('tck', $tck)->orderBy('accrual', 'DESC')->select('approved_incentive_id')->first();
        if ($q) {
            $a = \App\Models\ApprovedIncentive::where('id', $q->approved_incentive_id)->select('law_no')->first();
            if ($a) {
                if ($law != $a->law_no) {
                    return ['renk' => true, 'law' => $a->law_no];
                } else {
                    return ['renk' => false, 'law' => $a->law_no];
                }

            }
        }

        return null;
    }
}

if (!function_exists('canEmployeeHasTesvik')) {
    function canEmployeeHasTesvik($incentive, $approved_ids, $declaration_ids)
    {

        $job_start_date = getEmployeeJobStart($incentive, $approved_ids, $declaration_ids);
        if ($incentive->law == 6111) {

            $h = Incentive::where('tck', $incentive->tck)->where('law', 6111)->where('job_start', $job_start_date)->first();

            if ($h) {
                return true;
            }

            $date = $job_start_date->subMonth(5);
            $ay = $date->format('m');
            $yil = $date->format('Y');
            $date_k = $yil . '-' . $ay . '-01';


            $finish_control = IncentiveService::whereIn('approved_incentive_id', $approved_ids)->where('tck', $incentive->tck)->orderBy('job_finish', 'DESC')->whereBetween('job_finish', [$date_k, $job_start_date])->first();

            if ($finish_control) {
                return false;
            }
            return false;
        }

        if ($incentive->law == 17103 or $incentive->law == 7103 or $incentive->law == 27103 or $incentive->law == 14857) {
            $return = false;
            $date = $job_start_date->subMonth(2);
            $ay = $date->format('m');
            $yil = $date->format('Y');
            $date_k = $yil . '-' . $ay . '-01';


            $finish_control = IncentiveService::whereIn('approved_incentive_id', $approved_ids)->where('tck', $incentive->tck)->orderBy('job_finish', 'DESC')->whereBetween('job_finish', [$date_k, $job_start_date])->first();
            if ($finish_control) {
                if ($finish_control->gun <= 10) {
                    $return = true;
                } else {
                    $return = false;
                }

            }

            $job_finish_date = getEmployeeJobFinish($incentive, $approved_ids, $declaration_ids);
            if (isset($job_finish_date)) {
                $s = Carbon::parse($job_start_date);
                $f = Carbon::parse($job_finish_date);
                $diff = $s->diffInDays($f);

                if ($diff <= 10) {
                    $return = true;
                } else {
                    $return = false;
                }
            }

        }
        return $return;
    }
}
