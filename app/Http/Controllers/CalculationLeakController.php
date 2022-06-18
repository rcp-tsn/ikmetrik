<?php

namespace App\Http\Controllers;

use App\Exports\CurrentIncentivesExport;
use App\Http\Controllers\Controller;
use App\Models\Declaration;
use App\Models\Incentive;
use App\Models\LeakApprovedIncentive;
use App\Models\LeakGainIncentive;
use App\Models\LeakIncentiveService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Models\LeakIncentive;
use Excel;
use App\Exports\LeakExport;

class CalculationLeakController extends Controller
{
    public function calculation()
    {

        $company = session()->get('selectedCompany');
        $id = $company['id'];

        $one_date = session()->get('one_date');
        $two_date = session()->get('two_date');
        $dates2 = session()->get('dates2');

        foreach ($dates2 as $date)
        {

            $totalStafDate = $date;
            $date2 = explode('<option value="',$date);
            $dates3 = explode('"',$date2[1]);
            if ($one_date >= $dates3[0] )
            {
                $date = strip_tags($date);
                $date =   explode('/',$date);
                $date = implode('-',$date);
                $dates[] = $date.'-1';
            }
        }
        $dates =  array_reverse($dates);
        foreach ($dates as $date) {

# AYNI VERİ ÇEKME KONTROLÜ VE ÇÖZÜMÜ BAŞLA
        $updates = Incentive::where('sgk_company_id', $id)->orderby('id', 'DESC')->get();
        foreach ($updates as $update) {
            $find = Incentive::where('sgk_company_id', $update->sgk_company_id)->where('tck', $update->tck)->where('law', $update->law)->where('start', $update->start)->where('finish', $update->finish)->get()->count();
            if ($find != 1) {
                Incentive::where('id', $update->id)->forceDelete();
            }
        }
# AYNI VERİ ÇEKME KONTROLÜ VE ÇÖZÜMÜ BİTİŞ


            session(['date' => $date]);
            $totalStafDate = $date;
            $date3 = $date;
            $date = Carbon::createFromFormat('Y-m-d', $date);
            $date3 = Carbon::createFromFormat('Y-m-d', $date3);

            $date2 = $date->startofMonth()->subMonth()->endOfMonth()->toDateString(); // Bir önceki ayın son günü
            $incitementDate = $date3->startofMonth()->endOfMonth()->toDateString(); // Bir önceki ayın ilk günü


            // 2025-02-25 ileri tarih güncellemesi


            $ApprovedIncentives = LeakApprovedIncentive::with('leak_incentive_services')
                ->where("sgk_company_id", $id)
                ->where('accrual', $totalStafDate)
                ->get()
                ->toArray();


            $totalStaff = [];

            foreach ($ApprovedIncentives as $Personels) {

                foreach ($Personels['leak_incentive_services'] as $service) {
                    if ($service['tck'] != 0 )
                    {
                        if ($Personels['genus'] != 'IPTAL')
                        {
                            foreach ($totalStaff as $staff)
                            {
                                if ($staff == $service['tck'])
                                {
                                    unset($totalStaff[$service['tck']]);
                                }
                            }
                        }
                        $totalStaff[$service['tck']] = true;
                    }


                }

            }

            $incentives = LeakIncentive::with('incentive_service.leak_approvied_incentive')
                ->where('sgk_company_id', $id)
                ->where('finish', '>', $date2)
                ->where('start', '<=', $incitementDate)
                ->where('min_personel', '<', count($totalStaff))
                ->whereIn('law', [6111, 17103, 27103, 27256, 3294])
                ->where('days', '>', 0)
                ->get()
                ->groupBy('law')
                ->toArray();




            $FilterIncentives = [];
            $FilterIncentives['info'] = [];
            $FilterIncentives['veteran'] = [];
            $infoPersonals = [];
            $veteranPersonals = [];

            $notDayPersonals = [];
            $personelOrtalamaTotal = [];
            foreach ($incentives as $law => $laws) {

                $eklenecekSigortali = [];
                $personals = [];

                foreach ($laws as $personal) {

                    $totalStaffEx = count($totalStaff) - $personal['min_personel'];

                    // Ortalama kişi filtresi
                    if (!isset($eklenecekSigortali[$personal['min_personel']])) {
                        $eklenecekSigortali[$personal['min_personel']] = 0;
                    }

                    foreach ($personal['incentive_service'] as $incentive_services) {
                        if (isset($incentive_services['leak_approvied_incentive'][0])) {
                            $personalNew = $personal;

                            $personalNew['incentive_service'] = $incentive_services;

                            if ($incentive_services['leak_approvied_incentive'][0]['sgk_company_id'] == $id) {
                                // Ortalaması aynı olan diğer çalışanlarla birlikte çalışan sayısını geçiyor mu?
                                if ($totalStaffEx > $eklenecekSigortali[$personal['min_personel']]) {
                                    $eklenecekSigortali[$personal['min_personel']] = $eklenecekSigortali[$personal['min_personel']] + 1;
                                    if (decoct((int)$incentive_services['leak_approvied_incentive'][0]['document_type']) == "2") {
                                        $veteranPersonals[] = $personal;
                                    } elseif ($incentive_services['gun'] != 0 and $personalNew['law'] == 7252) {
                                        $personals[] = $personalNew;
                                    } elseif ($personal['law'] != 7252) {
                                        $personals[] = $personalNew;
                                    } else {
                                        $infoPersonals[] = $personalNew;
                                        // TODO - Hak etmeyen 1 (Şartları karşılamıyor)
                                    }
                                } else {
                                    // Ortalaması aynı olan diğer çalışanlarla birlikte çalışan sayısını geçiyorsa buraya ekle
                                    $notDayPersonals[] = $personalNew;
                                    // TODO - Hak etmeyen 2 (Ortalaması tutuyor fakat aynı ortalamaya sahip toplam çalışana göre fazla kişi var)
                                }

                            }
                        }


                        if ($law != 7252) {
                            $personelOrtalamaTotal[$law][$personal['min_personel']] = $eklenecekSigortali[$personal['min_personel']];

                        }

                    }
                    $FilterIncentives[$law] = $personals;

                    # Çalışan sayısı yüzünden teşvik alamayanları ekle
                }
            }

            $notIncentives = LeakIncentive::with('incentive_service.leak_approvied_incentive')
                ->where('leak_incentives.sgk_company_id', $id)
                ->where('finish', '>', $incitementDate)
                ->where('start', '<=', $date2)
                ->where('min_personel', '>', count($totalStaff))
                ->whereIn('law', [6111, 17103, 27103, 27256, 3294])
                ->where('days', '>', 0)
                ->get()
                ->groupBy('law')
                ->toArray();
// çalışan sayısı yüzünden yararlanamayanlar BİTİŞ


            $incitements = [];
            $multipleLaw = [];

            foreach ($FilterIncentives as $law => $personels) {

                $lawPersonels = [];
                foreach ($personels as $personel) {

                    if (isset($multipleLaw[$personel['tck']])) {
                        $multipleLaw[$personel['tck']][] = $law;
                    } else {
                        $multipleLaw[$personel['tck']] = [];
                        $multipleLaw[$personel['tck']][] = $law;
                    }
                    $lawPersonel = [];
                    $lawPersonel['sgk_company_id'] = $personel['sgk_company_id'];
                    $lawPersonel['document_type'] = $personel['incentive_service']['leak_approvied_incentive'][0]['document_type'];
                    $lawPersonel['name'] = $personel['incentive_service']['isim'];
                    $lawPersonel['surname'] = $personel['incentive_service']['soyisim'];
                    $lawPersonel['tck'] = $personel['tck'];
                    $lawPersonel['old_law'] = $personel['incentive_service']['leak_approvied_incentive'][0]['law_no'];
                    $lawPersonel['law'] = $law;
                    if ($lawPersonel['old_law'] == $lawPersonel['law']) {
                        $lawPersonel['law_different'] = false;
                    } else {
                        $lawPersonel['law_different'] = true;
                    }
                    $lawPersonel['start'] = $personel['start'];
                    $lawPersonel['finish'] = $personel['finish'];
                    $lawPersonel['job_start'] = $personel['job_start'];
                    if ($personel['job_finish'] == "2025-02-25") {
                        $lawPersonel['job_finish'] = "-";
                    } else {
                        $lawPersonel['job_finish'] = $personel['job_finish'];
                    }
                    $lawPersonel['min_personel'] = $personel['min_personel'];
                    if ($law == 7252) {
                        $lawPersonel['days'] = ($personel['days'] == 9999) ? "- " : $personel['days'];
                    } else {
                        $lawPersonel['days'] = $personel['incentive_service']['gun'];
                    }
                    $lawPersonel['ucret'] = $personel['incentive_service']['ucret_tl'];
                    $lawPersonel['ikramiye'] = $personel['incentive_service']['ikramiye_tl'];
                    if ($law == 7252) {
                        $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['incentive_service']['ucret_tl'], $personel['incentive_service']['ikramiye_tl'], $personel['days']);
                    } else {
                        $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['incentive_service']['ucret_tl'], $personel['incentive_service']['ikramiye_tl'], $personel['incentive_service']['gun']);
                    }

                    $lawPersonels[] = $lawPersonel;
                }
                $incitements[$law] = $lawPersonels;
            }

// raporda yeşil gözükme durumunda yapılan değişiklik
            foreach ($multipleLaw as $tck => $multi) {
                $multipleLaw[$tck] = array_unique($multi);
                // count(array_unique($multi));
            }
//bitiş

            $totalStaff = count($totalStaff);


            // TOPLAM HAK EDEN SAYISI VE TOPLAM HAKEDİŞLER BAŞLANGIÇ
            $totalIncitements = [];

            foreach ($incitements as $totalIncitementLaw) {
                foreach ($totalIncitementLaw as $totalIncitement) {

                    $s2law = $totalIncitement['law'];
                    if (in_array($s2law, [27103, 17103, 7103, 14857])) {
                        $s2law = 7103;
                    }

                    $price = $s2law . "-price";
                    $count = $s2law . "-count";

                    if (isset($totalIncitements['tesvik'])) {

                        $totalIncitements[$price] = $totalIncitements[$price] + $totalIncitement['tesvik'];
                        $totalIncitements[$count] = $totalIncitements[$count] + 1;

                    } else {
                        $totalIncitements[$price] = $totalIncitement['tesvik'];
                        $totalIncitements[$count] = 1;
                    }


                }
            }

            // TOPLAM HAK EDEN SAYISI VE TOPLAM HAKEDİŞLER BİTİŞ

//        declaration yoksa listeden çıkar
            foreach ($notIncentives as $key => $incentive) {
                foreach ($incentive as $value => $incentiv) {
                    if (!empty($incentiv['incentive_service'] and !empty($incentiv['incentive_service'][0]['leak_approvied_incentive']))) {

                    } else {

                        //echo $key .' '. $value.'<br>';
                        unset($notIncentives[$key][$value]);
                    }
                }
            }
            // declaration bitiş



            $notDaysInfo = notDaysInfo2(['notIncentives' => $notIncentives, 'notDayPersonals' => $notDayPersonals]);

            // ortalama yüzünden yararlanamayanlar yeşil gözükme

            // En Çok Kazanç Getiren Kişi Basılması İçin Ortalamadan Çıkaralıcak
            //yani kişinin kazancı en karlıysa ama ortalamadan dolayı yararlanamıyorsa onu yukarı alacak düşük olanı aşşağıya indiricek

            if (isset($notDaysInfo['notDayPersonals'])) {
                foreach ($notDaysInfo['notDayPersonals'] as  $law => $ortalamalar) {
                    foreach ($ortalamalar as $ortalama => $kisilers) {
                        foreach ($kisilers as $tc => $personel) {

                                    if (isset($incitements[$law]))
                                    {

                                        foreach ($incitements[$law] as $key => $incentiment)
                                        {
                                            $deger1 = [];
                                            $deger2 = [];
                                            $data = enDusukKazanc($incitements[$law],$personel);



                                                if (isset($notDaysInfo['notDayPersonals'][$law][$ortalama][$tc]) and $personel['tesvik'] > $data[1])
                                                {
                                                    $deger1 = $notDaysInfo['notDayPersonals'][$law][$ortalama][$tc];
                                                    $deger2 = $incitements[$law][$data[0]];
                                                    unset($incitements[$law][$data[0]]);
                                                    unset($notDaysInfo['notDayPersonals'][$law][$ortalama][$tc]);
                                                    $notDaysInfo['notDayPersonals'][$law][$ortalama][$deger2['tck']] = $deger2;
                                                    $incitements[$law][$data[0]] = $deger1;
                                                }

                                            }

                                    }
                        }
                    }

                }
            }

            $multipleLaw = [];

            if (isset($notDaysInfo['notDayPersonals'])) {
                foreach ($notDaysInfo['notDayPersonals'] as $day => $kanunlar) {
                    foreach ($kanunlar as $kanuns) {
                            foreach ($kanuns as $personel) {

                                if (isset($multipleLaw[$personel['tck']])) {
                                    if (!isset($multipleLaw[$personel['tck']][$personel['law']])) {
                                        $multipleLaw[$personel['tck']][] = $personel['law'];
                                    }

                                }
                                else
                                {
                                    $multipleLaw[$personel['tck']] = [];
                                    $multipleLaw[$personel['tck']][] = $personel['law'];
                                }

                            }
                    }

                }
            }




            // yararlandığı kanundan dolayı koydum yukarda yaptığımız değişiklikten dolayı multiLaw koyamadık

            if(isset($incitements))
            {
                foreach ($incitements as $law => $incitementss)
                {
                    foreach ($incitementss as $incitement)
                    {
                        if (isset($multipleLaw[$incitement['tck']])) {
                            $multipleLaw[$incitement['tck']][] = $law;
                        } else {
                            $multipleLaw[$incitement['tck']] = [];
                            $multipleLaw[$incitement['tck']][] = $law;
                        }
                    }

                }
            }

            $values = cw_enerji2($incitements, $multipleLaw , isset($notDaysInfo['notDayPersonals']) ? $notDaysInfo['notDayPersonals'] : $null = [] );
            $incitements = $values['incitements'];
            $notDaysInfo['notDayPersonals'] = $values['notDaysInfo'];

            if (isset($notDaysInfoPersonelData))
            {
                foreach ($notDaysInfoPersonelData as  $data)
                {

                        $incitements[$law][] = $data;
                }
            }

            // En Çok Kazanç Getiren Kişi Basılması İçin Ortalamadan Çıkaralıcak Son
           // $incitements = cw_enerji2($incitements, $multipleLaw);



            //V2 Kontrol Bölümü Burada Kişinin Hangi Kanundan Yararlanılıdğına Bakılacak Yanlış Kanundan Yararlanıldıysa Kayıp Tablosuna Atılacak Başlangıç
            foreach ($incitements as $incitement)
            {
                if (count($incitement) > 0)
                {
                    foreach ($incitement as $incitem)
                    {
                        $leak_incentive_service = LeakIncentiveService::where('tck',$incitem['tck'])
                            ->where('sgk_company_id',$incitem['sgk_company_id'])
                            ->where('accrual',$totalStafDate)
                            ->first();
                        if ($leak_incentive_service)
                        {
                            $approvied = LeakApprovedIncentive::find($leak_incentive_service->approved_incentive_id);

                                $lawPersonel = tesvikHesapla($approvied->law_no, $leak_incentive_service->ucret_tl, $leak_incentive_service->ikramiye_tl, $leak_incentive_service->gun);
                        }
                        else
                        {
                            $lawPersonel = 0;
                        }


                        if ($approvied->law_no != $incitem['law'] and $lawPersonel < $incitem['tesvik'] )
                        {

                            $leakage_incitements[] = array('ortalama' => $incitem['min_personel'],'date' => $totalStafDate,'tck' => $incitem['tck'],'name'=>$incitem['name'],'surname'=>$incitem['surname'],'document_type' => $incitem['document_type'],'old_law' => $approvied->law_no,'law'=> $incitem['law'],'lucraite_money'=>$incitem['tesvik'],'ucret'=>$incitem['ucret']);
                        }

                    }
                }
            }


                if (count($leakage_incitements) <= 0)
                {
                    $leakage_incitements = [];
                }




            //V2 Kontrol Bölümü Burada Kişinin Hangi Kanundan Yararlanılıdğına Bakılacak Yanlış Kanundan Yararlanıldıysa Kayıp Tablosuna Atılacak Bitiş
            $hatalar = session()->get('errorss');
            if (isset($hatalar[$id]))
            {
                $errors = $hatalar[$id];
            }
            else
            {
                $errors = [];
            }

            /*

                    session(['incentives_finish' => $incentives_finish]);
                    session(['excel_incitements' => $incitements]);
                    session(['excel_totalStaff' => $totalStaff]);
                    session(['excel_company' => $company]);
                    session(['excel_incitementDate' => $incitementDate]);
                    session(['excel_multipleLaw' => $multipleLaw]);
                    session(['excel_totalIncitements' => $totalIncitements]);
                    session(['excel_notDaysInfo' => $notDaysInfo]);
                    session(['excel_personelOrtalamaTotal' => $personelOrtalamaTotal]);
            */

            return view('losts.table', compact('incitements', 'totalStaff', 'company', 'incitementDate', 'multipleLaw', 'totalIncitements', 'notDaysInfo', 'personelOrtalamaTotal','leakage_incitements','errors'));








           //  return Excel::store(new CurrentIncentivesExport($incitements, $totalStaff, $company, $incitementDate, $multipleLaw, $totalIncitements, $notDaysInfo, $personelOrtalamaTotal, $incentives_finish), $excel_file_name . '.xlsx', 'excel_reports');
            // Excel::download(new CurrentIncentivesExport($incitements, $totalStaff, $company, $incitementDate, $multipleLaw, $totalIncitements, $notDaysInfo, $personelOrtalamaTotal, $incentives_finish), $company["name"] . ' - ' . date("Y-m-d") . '.xlsx');
        }
/*
        $totalStaff = session()->get('excel_totalStaff');
        $company = session()->get('excel_company');
        $incitementDate = session()->get('excel_incitementDate');

        return view('losts.lost_table', compact( 'totalStaff', 'company', 'incitementDate','leakage_incitements'));
*/


    }
}

