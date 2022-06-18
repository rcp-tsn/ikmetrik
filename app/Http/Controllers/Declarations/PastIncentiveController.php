<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\ActiveIncentive;
use App\Models\ApprovedIncentive;
use App\Models\Declaration;
use App\Models\DeclarationService;
use App\Models\GainIncentive;
use App\Models\Incentive;
use App\Models\IncentiveService;
use App\Models\KcoIncentive;
use App\Models\SgkCompany;
use App\Models\SumIncentive;
use Carbon\Carbon;
use Curl;
use FilterGenerator;
use DateTime;
use Gufy\PdfToHtml\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Response;


class PastIncentiveController extends Controller
{

    public function __construct()
    {

    }

    public function index($law_no)
    {
        $sgk_company = getSgkCompany();
        $laws = [6111, 7103, 17103, 27103,14857];
        foreach($laws as $key => $law) {
            $approved_incentives = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
                ->where('law_no', $law)
                ->orderby('accrual', 'DESC')
                ->select('accrual')
                ->where('accrual', '>=', Carbon::now()->startOfMonth()->subMonth(7)->format('Y-m-d'))
                ->groupBy('accrual')
                ->get();

            $approvedIncentiveAll = ApprovedIncentive::where("sgk_company_id", $sgk_company->id)
                ->with('incentive_services')
                ->orderby('accrual', 'DESC')
                ->get()
                ->groupBy('accrual');
            //dd($declarationAll->first());
            $totalStaffArray = [];
            foreach ($approvedIncentiveAll->first() as $approvedPersonels) {
                foreach ($approvedPersonels->incentive_services as $service) {
                    $totalStaffArray[] = $service->tck;
                }
            }


            $totalStaff = count($totalStaffArray);
            foreach($approved_incentives as $approved_incentive) {
                $active_incentive_count = ActiveIncentive::where('sgk_company_id', $sgk_company->id)->where('kanun', $law)->where('accrual', $approved_incentive->accrual)->count();
                $active_incentive_hakedis = ActiveIncentive::where('sgk_company_id', $sgk_company->id)->where('kanun', $law)->where('accrual', $approved_incentive->accrual)->sum('hakedis');



                $sum_incentive = SumIncentive::where('sgk_company_id', $sgk_company->id)->where('kanun', $law)->where('accrual', $approved_incentive->accrual)->first();

                if(!$sum_incentive) {

                    $sum_incentive = new SumIncentive();
                }

                $yararlanilmis = 0;
                $tutar = 0;

                $approved_incentives = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->where('law_no', $law)->where('accrual', $approved_incentive->accrual)->get();
                foreach($approved_incentives as $approved_incentive) {
                    $values = IncentiveService::where('approved_incentive_id', $approved_incentive->id)->get();

                    foreach($values  as $value) {
                        $tutar += tesvikHesapla($approved_incentive->accrual, $law, $value->ucret_tl, $value->ikramiye_tl, $value->gun, true);

                    }
                    $yararlanilmis += count($values);
                }


                $sum_incentive->sgk_company_id = $sgk_company->id;
                $sum_incentive->kanun = $law;
                $sum_incentive->accrual = $approved_incentive->accrual;
                $sum_incentive->calisan_sayisi = $totalStaff;
                $sum_incentive->yararlanabilen = $active_incentive_count;
                $sum_incentive->toplam_tutar = $active_incentive_hakedis;
                $sum_incentive->yararlanilmis =$yararlanilmis;
                $sum_incentive->yararlanilmis_tutar = $tutar;
                $sum_incentive->yararlanilacak = 0;
                $sum_incentive->yararlanilacak_tutar = 0;

                $sum_incentive->save();

            }
        }
        if ($law_no == 7103) {
            $sum_incentives = SumIncentive::where('sgk_company_id', $sgk_company->id)->whereIn('kanun', [7103,17103,27103])->groupBy('accrual')->select('accrual')->get();
        } else {
            $sum_incentives = SumIncentive::where('sgk_company_id', $sgk_company->id)->where('kanun', $law_no)->groupBy('accrual')->select('accrual')->get();
        }


        return view('incentives.past.index', compact('sgk_company', 'sum_incentives', 'law_no'));
    }


    public function checkIncentive($date, $law)
    {
        $sgk_company = getSgkCompany();

        if ($law == 7103) {
            $approved_incentive_ids = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->whereIn('law_no', [7103,17103,27103])->where('accrual', $date)->pluck('id');
        } else {
            $approved_incentive_ids = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->where('law_no', $law)->where('accrual', $date)->pluck('id');
        }


        $incentive_services = IncentiveService::whereIn('approved_incentive_id', $approved_incentive_ids)->orderBy('isim', 'ASC')->get();
        return view('incentives.past.index_'.$law, compact('sgk_company', 'incentive_services', 'law', 'date'));
    }
}
