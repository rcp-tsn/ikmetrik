<?php

namespace App\Http\Controllers\Declarations;

use App\Http\Controllers\Controller;
use App\Models\GainIncentive;
use App\Models\SgkCompany;
use App\Models\Company;
use DB;
use Redirect;
use Illuminate\Http\Request;
class GainIncentiveController extends Controller
{


    public function index(Request $request)
    {

        $all = false;
        $id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($id);
        $company = Company::find($sgk_company->company_id);
        $data_gain = [];
        $data_gain_total = [];

        $v_6111 = 0;
        $v_27103 = 0;
        $v_5510 = 0;
        $v_14857 = 0;
        $v_7252 = 0;
        $v_3294 = 0;
        $v_total = 0;
        if (isset($request->date)) {
            $gain_incentive_first = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->where('accrual', $request->date)->select('accrual')->first();
        } else {
            $gain_incentive_first = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->select('accrual')->first();
        }

        if(!$gain_incentive_first) {
            return Redirect::back()->with('danger', 'Seçili / Aktif aya ait teşvik raporu alınamadı. ');
        }

            $gain_incentives_for_groups = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->groupBy('accrual')->selectRaw('sum(law_6111) as total_law_6111, sum(law_27103) as total_law_27103, sum(law_7252) as total_law_7252,sum(law_3294) as total_law_6111, accrual')->get();
            foreach($gain_incentives_for_groups as $gain_incentives_for_group) {
                $data_gain[] = [
                    'tarih' => getFullMonthName($gain_incentives_for_group->accrual->format('M')).'/'.$gain_incentives_for_group->accrual->format('Y'),
                    '6111' => $gain_incentives_for_group->total_law_6111,
                    '27103' => $gain_incentives_for_group->total_law_27103,
                    '7252' => $gain_incentives_for_group->total_law_7252,
                    '3294' => $gain_incentives_for_group->total_law_3294,
                ];
            }

            $gain_incentives = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->get();
                foreach($gain_incentives as $gain_incentive) {
                    $v_6111 += $gain_incentive->law_6111;
                    $v_5510 += $gain_incentive->law_5510;
                    $v_27103 += $gain_incentive->law_27103;
                    $v_14857 += $gain_incentive->law_14857;
                    $v_7252 += $gain_incentive->law_7252;
                    $v_3294 += $gain_incentive->law_3294;
                    $v_total += $gain_incentive->total_amount;


                }

            $data_gain_total[] = [
                'tarih' => getFullMonthName($gain_incentive_first->accrual->format('M')),
                '6111' => $v_6111,
                '7252' => $v_7252,
                '27103' => $v_27103,
                '5510' => $v_5510,
                '14857' => $v_14857,
                '3294' => $v_3294,
                'total' => $v_total
            ];

        $gain_array = json_encode($data_gain);


        $active_gains = [];
        if ($gain_incentive_first) {
            $active_gains = GainIncentive::where('sgk_company_id', $sgk_company->id)->where('accrual', $gain_incentive_first->accrual)->get();
        }
        return view('incentives.gain_incentives', compact('data_gain_total', 'gain_incentives', 'sgk_company', 'gain_array', 'active_gains', 'gain_incentive_first', 'data_gain', 'all', 'company'));

    }


    public function allIndex(Request $request)
    {
        $all = true;
        $id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($id);
        $company = Company::find($sgk_company->company_id);
        $sgk_company_ids = SgkCompany::where('company_id', $sgk_company->company_id)->pluck('id');

        $data_gain = [];
        $data_gain_total = [];

        $v_6111 = 0;
        $v_27103 = 0;
        $v_5510 = 0;
        $v_7252 = 0;
        $v_14857 = 0;
        $v_3294 = 0;
        $v_total = 0;
        if (isset($request->date)) {
            $gain_incentive_first = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->orderBy('accrual', 'DESC')->where('accrual', $request->date)->select('accrual')->first();
        } else {
            $gain_incentive_first = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->orderBy('accrual', 'DESC')->select('accrual')->first();
        }


        $gain_incentives_for_groups = GainIncentive::whereIn('sgk_company_id',$sgk_company_ids)->orderBy('accrual', 'DESC')->groupBy('accrual')->selectRaw('sum(law_6111) as total_law_6111, sum(law_27103) as total_law_27103, sum(law_7252) as total_law_7252, sum(law_3294) as toplam_law_3294 ,accrual')->get();

        foreach($gain_incentives_for_groups as $gain_incentives_for_group) {
            $data_gain[] = [
                'tarih' => getFullMonthName($gain_incentives_for_group->accrual->format('M')).'/'.$gain_incentives_for_group->accrual->format('Y'),
                '6111' => $gain_incentives_for_group->total_law_6111,
                '27103' => $gain_incentives_for_group->total_law_27103,
                '7252' => $gain_incentives_for_group->total_law_7252,
                '3294' => $gain_incentives_for_group->total_law_3294,
            ];
        }
        $gain_incentives = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->orderBy('accrual', 'DESC')->get();

        foreach($gain_incentives as $gain_incentive) {
            $v_6111 += $gain_incentive->law_6111;
            $v_5510 += $gain_incentive->law_5510;
            $v_27103 += $gain_incentive->law_27103;
            $v_14857 += $gain_incentive->law_14857;
            $v_7252 += $gain_incentive->law_7252;
            $v_3294 += $gain_incentive->law_3294;
            $v_total += $gain_incentive->total_amount;


        }

        $data_gain_total[] = [
            'tarih' => getFullMonthName($gain_incentive_first->accrual->format('M')),
            '6111' => $v_6111,
            '27103' => $v_27103,
            '5510' => $v_5510,
            '7252' => $v_7252,
            '14857' => $v_14857,
            '3294' => $v_3294,
            'total' => $v_total
        ];

        $gain_array = json_encode($data_gain);


        $active_gains = [];
        if ($gain_incentive_first) {
            $active_gains = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->where('accrual', $gain_incentive_first->accrual)->get();
        }
        return view('incentives.gain_incentives', compact('data_gain_total', 'gain_incentives', 'sgk_company', 'gain_array', 'active_gains', 'gain_incentive_first', 'data_gain', 'all', 'company'));

    }


}
