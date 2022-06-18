<?php

namespace App\Http\Controllers\Declarations;

use App\DataTables\DepartmentsDataTable;
use App\Exports\CurrentIncentivesExport;
use App\Http\Controllers\Controller;
use App\Models\ActiveIncentive;
use App\Models\ApprovedIncentive;
use App\Models\DeclarationService;
use App\Models\DisabledIncentive;
use App\Models\Incentive;
use App\Models\IncentiveService;
use App\Models\SgkCompany;
use App\Models\Declaration;
use Excel;
use Carbon\Carbon;

class IncentiveController extends Controller
{


    public function currentIncentives()
    {

        $company = session()->get('selectedCompany');
        $id = $company['id'];


# AYNI VERİ ÇEKME KONTROLÜ VE ÇÖZÜMÜ BAŞLA
        $updates = Incentive::where('sgk_company_id', $id)->orderby('id', 'DESC')->get();
        foreach ($updates as $update) {
            $find = Incentive::where('sgk_company_id', $update->sgk_company_id)->where('tck', $update->tck)->where('law', $update->law)->where('start', $update->start)->where('finish', $update->finish)->get()->count();
            if ($find != 1) {
                Incentive::where('id', $update->id)->forceDelete();
            }
        }
# AYNI VERİ ÇEKME KONTROLÜ VE ÇÖZÜMÜ BİTİŞ

        $date = Carbon::now()->startofMonth()->subMonth()->endOfMonth()->toDateString(); // Bir önceki ayın son günü
        $incitementDate = Carbon::now()->startofMonth()->subMonth()->toDateString(); // Bir önceki ayın ilk günü


        // 2025-02-25 ileri tarih güncellemesi

        $declatationAll = Declaration::where("sgk_company_id", $id)
            //->with('services')
            ->orderby('declarations_date', 'DESC')
            ->get()
            ->groupBy('declarations_date');


        $totalStaff = [];
        foreach ($declatationAll->first() as $declarationPersonels) {
            foreach ($declarationPersonels->services as $service) {
                $totalStaff[$service->tck] = true;
            }
        }

        /*
                $incentives = Incentive::with(['declaration_service' => function ($q) {
                    $q->with('declaration')->has('declaration');
                }])
                    ->has('declaration_service')
                    ->where('sgk_company_id', $id)
                    ->where('finish', '>', $incitementDate)
                    ->where('start', '<=', $date)
                    ->where('min_personel', '<', count($totalStaff))
                    ->whereIn('law', [6111, 17103, 27103, 27256, 7252])
                    ->where('days', '>', 0)
                    ->get()
                    ->groupBy('law')
                    ->toArray();
        */

        $incentives = Incentive::with('declaration_service.declaration')
            ->where('sgk_company_id', $id)
            ->where('finish', '>', $incitementDate)
            ->where('start', '<=', $date)
            ->where('min_personel', '<', count($totalStaff))
            ->whereIn('law', [6111, 17103, 27103, 27256, 7252,3294,7319,14857])
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

                // Ortalama kişi filtresi
                $a = count($personal['declaration_service']);


                //Önceki Aydaki Hakedişleri Kaldırma Başlangıç
                //Kişinin Önceki Aydaki Teşvik Kazanımlarını Rapora Basmaması İçin En son Aydaki Verileri Ekleme
                if ($a > 1) {

                    $b = $a - 1;

                    $en_son_ay = $personal['declaration_service'][$b]['declaration'][0]['declarations_date'];

                    $en_son_ay = explode('-', $en_son_ay);


                    foreach ($personal['declaration_service'] as $key => $declaration) {
                        if (isset($declaration['declaration'][0])) {


                            $ay = explode('-', $declaration['declaration'][0]['declarations_date']);

                            if ($en_son_ay[1] > $ay[1]) {

                                unset($personal['declaration_service'][$key]);
                            }
                        }


                    }
                }

                //Önceki Aydaki Hakedişleri Kaldırma Bitiş

                foreach ($personal['declaration_service'] as $declaration_services) {
                    if (isset($declaration_services['declaration'][0])) {
                        $personalNew = $personal;


                        $personalNew['declaration_service'] = $declaration_services;
                        if ($declaration_services['declaration'][0]['sgk_company_id'] == $id) {
                            // Ortalaması aynı olan diğer çalışanlarla birlikte çalışan sayısını geçiyor mu?
                            if ($totalStaffEx > $eklenecekSigortali[$personal['min_personel']]) {
                                $eklenecekSigortali[$personal['min_personel']] = $eklenecekSigortali[$personal['min_personel']] + 1;
                                if (decoct((int)$declaration_services['declaration'][0]['document_type']) == "2"  ) {
                                    $veteranPersonals[] = $personal;
                                } elseif ($declaration_services['gun'] != 0 and $personalNew['law'] == 7252) {
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


        //$FilterIncentives['veteran'] = $veteranPersonals;
        // TODO - Emekli abilerimiz buraya
        // Ortalama yüzünden yararlanamayanlar BAŞLANGIÇ
        /*
        $notIncentives = Incentive::with(['declaration_service' => function ($q) {
            $q->with('declaration')->has('declaration');
        }])
            ->has('declaration_service')
            ->where('incentives.sgk_company_id', $id)
            ->where('finish', '>', $incitementDate)
            ->where('start', '<=', $date)
            ->where('min_personel', '>', count($totalStaff))
            ->whereIn('law', [6111, 17103, 27103, 27256])
            ->where('days', '>', 0)
            ->get()
            ->groupBy('law')
            ->toArray();
        // Ortalama yüzünden yararlanamayanlar BİTİŞ
*/

        $notIncentives = Incentive::with('declaration_service.declaration')
            ->where('incentives.sgk_company_id', $id)
            ->where('finish', '>', $incitementDate)
            ->where('start', '<=', $date)
            ->where('min_personel', '>', count($totalStaff))
            ->whereIn('law', [6111, 17103, 27103, 27256,3294,7319])
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
                $lawPersonel['document_type'] = $personel['declaration_service']['declaration'][0]['document_type'];
                $lawPersonel['name'] = $personel['declaration_service']['isim'];
                $lawPersonel['surname'] = $personel['declaration_service']['soyisim'];
                $lawPersonel['tck'] = $personel['tck'];
                if ($company['company_id'] == 182)
                {

                    $old_law = old_law($personel['tck'],$personel['sgk_company_id'],$incitementDate);
                    $lawPersonel['old_law'] = $old_law;
                }
                else
                {
                    $lawPersonel['old_law'] = $personel['declaration_service']['declaration'][0]['law'];
                }

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
                if ($personel['start'] == "2000-01-01") {
                    $lawPersonel['job_finish'] = "../..";
                } if($personel['finish'] == '2030-01-01') {
                    $lawPersonel['finish'] = '../..';
                }
                $lawPersonel['min_personel'] = $personel['min_personel'];
                if ($law == 7252) {
                    $lawPersonel['days'] = ($personel['days'] == 9999) ? "- " : $personel['days'];
                } else {
                    $lawPersonel['days'] = $personel['declaration_service']['gun'];
                }
                $lawPersonel['ucret'] = $personel['declaration_service']['ucret_tl'];
                $lawPersonel['ikramiye'] = $personel['declaration_service']['ikramiye_tl'];
                if ($law == 7252) {
                    $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['declaration_service']['ucret_tl'], $personel['declaration_service']['ikramiye_tl'], $personel['days']);
                }

                elseif($law == 14857)
                {
                    $lawPersonel['tesvik'] =  tesvikHesapla($personel['law'], $personel['declaration_service']['ucret_tl']);
                }

                else {
                    $lawPersonel['tesvik'] = tesvikHesapla($personel['law'], $personel['declaration_service']['ucret_tl'], $personel['declaration_service']['ikramiye_tl'], $personel['declaration_service']['gun']);
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
                if(!empty($incentiv['declaration_service'] and !empty($incentiv['declaration_service'][0]['declaration']))) {

                } else {

                    //echo $key .' '. $value.'<br>';
                    unset($notIncentives[$key][$value]);
                }
            }
        }
        // declaration bitiş


        $notDaysInfo = notDaysInfo(['notIncentives' => $notIncentives, 'notDayPersonals' => $notDayPersonals]);
        // ortalama yüzünden yararlanamayanlar yeşil gözükme

        if (isset($notDaysInfo['notDayPersonals'])){
            foreach ($notDaysInfo['notDayPersonals'] as $day => $kanunlar) {
                foreach ($kanunlar as $kanuns) {
                    foreach ($kanuns as $tc => $documents) {
                        foreach ($documents as $personel) {

                            if (isset($multipleLaw[$tc])) {
                                if (!isset($multipleLaw[$tc][$personel['law']])) {
                                    $multipleLaw[$tc][] = $personel['law'];
                                }

                            }

                        }


                    }
                }

            }
    }

        if ($company['company_id'] == 182) {

            //Bu Function Cw Enerji Raporda Değişiklik İstedi Diye Yaptım Raporda En Çok Para Kazandıran Kanundaki Kişileri Getirmesi İçin
            //Eski İşlemde Kişi Hangi Kanunlardan Yararlanma Hakkı Varsa Onları Kanun Kanun Listeliyordu
            $incitements = cw_enerji($incitements, $multipleLaw);
            //Cw Enerji Edit Bitiş

            //Teşvik Bitenler Başlangıç
            $incentives_finish = cw_enerji_incentives_finish($incitements,$incitementDate,$company);
            //Teşvik Başlangıç Biten Teşvik
        }
        else
        {
            $incentives_finish = [];
        }
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
        $disabledIncentives  = \DisabledIncentive::with('declaration_service.declaration')
        ->where('sgk_company_id', $id)
        ->where('finish', '>', $incitementDate)
        ->where('start', '<=', $date)
        ->get()
        ->toArray();


       foreach ($disabledIncentives as  $key => $disabledIncentive)
       {

           if (count($disabledIncentive['declaration_service']) == 0)
           {
               unset($disabledIncentives[$key]);
           }
          //
       }
        $disabledIncitements = [];
        foreach ($disabledIncentives as  $key => $disabledIncentive) {

                $kanun = null;
                $kazanc = tesvikHesapla(14857, $disabledIncentive['declaration_service'][0]['ucret_tl']);
                $incentive_service = IncentiveService::where('tck',$disabledIncentive['tck'])
                    ->where('accrual','2021-09-01')->first();

                if (!$incentive_service)
                {
                    $kanun = true;
                }
                else
                {

                    $approvied = ApprovedIncentive::find($incentive_service->approved_incentive_id);

                    if ($approvied->law_no != 14857)
                    {
                        $kanun = true;
                    }
                    else
                    {
                        $kanun = false;
                    }
                }
            if (isset($multipleLaw[$disabledIncentive['tck']])) {
                $multipleLaw[$disabledIncentive['tck']][] = 14857;
            } else {
                $multipleLaw[$disabledIncentive['tck']] = [];
                $multipleLaw[$disabledIncentive['tck']][] = 14857;
            }
           if ( $disabledIncentive['start'] == '2000-01-01')
           {
               $start = '--/--';

           }
           else
           {
               $start = $disabledIncentive['start'];

           }
           if ($disabledIncentive['finish'] == '2025-01-01')
           {
               $finish = '--/--';
           }
           else
           {
               $finish = $disabledIncentive['finish'];
           }

            $disabledIncitements[] = array('tck' => $disabledIncentive['tck'],
                    'name' => $disabledIncentive['declaration_service'][0]['isim'], 'surname' => $disabledIncentive['declaration_service'][0]['soyisim'],
                    'document_type' => $disabledIncentive['declaration_service'][0]['declaration'][0]['document_type'],
                    'tesvik' => $kazanc, 'old_law' => $disabledIncentive['declaration_service'][0]['declaration'][0]['law'],
                    'law' => 14857, 'start' => $start, 'finish' => $finish,
                    'job_start_date' => $disabledIncentive['declaration_service'][0]['job_start'], 'job_finish_date' => null,
                    'kanun'=> $kanun);

        }
        if (count($disabledIncitements) > 0)
        {
            $incitements[14857] = $disabledIncitements;
        }
*/

        $errors['iskur'] = session()->get('iskur_errors');
        $errors['errors'] = $errors;


        session(['errorrs'=>$errors]);
        session(['incentives_finish' => $incentives_finish]);
        session(['excel_incitements' => $incitements]);
        session(['excel_totalStaff' => $totalStaff]);
        session(['excel_company' => $company]);
        session(['excel_incitementDate' => $incitementDate]);
        session(['excel_multipleLaw' => $multipleLaw]);
        session(['excel_totalIncitements' => $totalIncitements]);
        session(['excel_notDaysInfo' => $notDaysInfo]);
        session(['excel_personelOrtalamaTotal' => $personelOrtalamaTotal]);


        return view('incentives.current_incentives', compact('incitements', 'totalStaff', 'company', 'incitementDate', 'multipleLaw', 'totalIncitements', 'notDaysInfo', 'personelOrtalamaTotal','incentives_finish','errors'));
    }

    public function excelExport()
    {

        $incentives_finish = session()->get('incentives_finish');
        $incitements = session()->get('excel_incitements');
        $totalStaff = session()->get('excel_totalStaff');
        $company = session()->get('excel_company');
        $incitementDate = session()->get('excel_incitementDate');
        $multipleLaw = session()->get('excel_multipleLaw');
        $totalIncitements = session()->get('excel_totalIncitements');
        $notDaysInfo = session()->get('excel_notDaysInfo');
        $personelOrtalamaTotal = session()->get('excel_personelOrtalamaTotal');
        $excel_file_name = $company["id"] . '-' . $company["registry_id"] . '-' . '-01';
        $errors = session()->get('errorrs');



        Excel::store(new CurrentIncentivesExport($incitements, $totalStaff, $company, $incitementDate, $multipleLaw, $totalIncitements, $notDaysInfo, $personelOrtalamaTotal,$incentives_finish,$errors), $excel_file_name . '.xlsx', 'excel_reports');
        return Excel::download(new CurrentIncentivesExport($incitements, $totalStaff, $company, $incitementDate, $multipleLaw, $totalIncitements, $notDaysInfo, $personelOrtalamaTotal,$incentives_finish,$errors), $company["name"] . ' - ' . date("Y-m-d") . '.xlsx');
    }

}
