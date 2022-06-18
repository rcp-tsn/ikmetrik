<?php

namespace App\Http\Controllers;
use App\Base\ApplicationController;
use App\Models\ApprovedIncentive;
use App\Models\Company;
use App\Models\Declaration;
use App\Models\GainIncentive;
use App\Models\GainReport;
use App\Models\SgkCompany;
use PDF;
use View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends ApplicationController
{
    protected $hashId = true;

    public function incentivesDetailed($id, Request $request)
    {


        $all = false;
        $sgk_company = SgkCompany::find($id);
        $company = Company::find($sgk_company->company_id);
        $data_gain = [];
        $data_gain2 = [];
        $data_gain_total = [];

        $v_6111 = 0;
        $v_27103 = 0;
        $v_5510 = 0;
        $v_7252 = 0;
        $v_14857 = 0;
        $v_total = 0;

        if (isset($request->date)) {
            $gain_incentive_first = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->where('accrual', $request->date)->select('accrual')->first();
        } else {
            $gain_incentive_first = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->select('accrual')->first();
        }

        if (isset($request->date)) {
            $gain_fatura = GainIncentive::where('sgk_company_id', $sgk_company->id)->where('accrual', $request->date)->orderBy('accrual', 'DESC')->groupBy('accrual')->selectRaw('sum(law_6111) as total_law_6111, sum(law_27103) as total_law_27103, sum(law_7252) as total_law_7252, accrual')->get();
        } else {
            $gain_fatura = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->groupBy('accrual')->selectRaw('sum(law_6111) as total_law_6111, sum(law_27103) as total_law_27103, sum(law_7252) as total_law_7252, accrual')->get();
        }

        $gain_incentives_for_groups = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->groupBy('accrual')->selectRaw('sum(law_6111) as total_law_6111, sum(law_27103) as total_law_27103, sum(law_7252) as total_law_7252, accrual')->get();

        $text_fatura = '["Tarih", "6111", "27103", "7252"],';
        foreach($gain_fatura as $gain_fatura_group) {
            $data_gain2[] = [
                'tarih' => getFullMonthName($gain_fatura_group->accrual->format('M')).'/'.$gain_fatura_group->accrual->format('Y'),
                '6111' => $gain_fatura_group->total_law_6111,
                '7252' => $gain_fatura_group->total_law_7252,
                '27103' => $gain_fatura_group->total_law_27103
            ];
            if ($gain_fatura_group->total_law_6111 > 0) {
                $one = $gain_fatura_group->total_law_6111;
            } else {
                $one = 0;
            }
            if ($gain_fatura_group->total_law_27103 > 0) {
                $two = $gain_fatura_group->total_law_27103;
            } else {
                $two = 0;
            }

            if ($gain_fatura_group->total_law_7252 > 0) {
                $three = $gain_fatura_group->total_law_7252;
            } else {
                $three = 0;
            }
            $text_fatura = $text_fatura."['".getFullMonthName($gain_fatura_group->accrual->format('M'))."/".$gain_fatura_group->accrual->format('Y')."',".$one.", ".$two.", ".$three."],";

        }
        $text = '["Tarih", "6111", "27103", "7252"],';
        foreach($gain_incentives_for_groups as $gain_incentives_for_group) {
            $data_gain[] = [
                'tarih' => getFullMonthName($gain_incentives_for_group->accrual->format('M')).'/'.$gain_incentives_for_group->accrual->format('Y'),
                '6111' => $gain_incentives_for_group->total_law_6111,
                '7252' => $gain_incentives_for_group->total_law_7252,
                '27103' => $gain_incentives_for_group->total_law_27103
            ];
            if ($gain_incentives_for_group->total_law_6111 > 0) {
                $one = $gain_incentives_for_group->total_law_6111;
            } else {
                $one = 0;
            }
            if ($gain_incentives_for_group->total_law_27103 > 0) {
                $two = $gain_incentives_for_group->total_law_27103;
            } else {
                $two = 0;
            }

            if ($gain_incentives_for_group->total_law_7252 > 0) {
                $three = $gain_incentives_for_group->total_law_7252;
            } else {
                $three = 0;
            }
            $text = $text."['".getFullMonthName($gain_incentives_for_group->accrual->format('M'))."/".$gain_incentives_for_group->accrual->format('Y')."',".$one.", ".$two.", ".$three."],";

        }
        $gain_incentives = GainIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->get();
        foreach($gain_incentives as $gain_incentive) {
            $v_6111 += $gain_incentive->law_6111;
            $v_5510 += $gain_incentive->law_5510;
            $v_27103 += $gain_incentive->law_27103;
            $v_14857 += $gain_incentive->law_14857;
            $v_7252 += $gain_incentive->law_7252;
            $v_total += $gain_incentive->total_amount;


        }

        $data_gain_total[] = [
            'tarih' => getFullMonthName($gain_incentive_first->accrual->format('M')),
            '6111' => $v_6111,
            '27103' => $v_27103,
            '5510' => $v_5510,
            '7252' => $v_7252,
            '14857' => $v_14857,
            'total' => $v_total
        ];
        $text = remove_last_string($text, ',');
        $text_fatura = remove_last_string($text_fatura, ',');
       $data_for_pdf_array = $text;
        $gain_array = json_encode($data_gain);
        $gain_array2 = json_encode($data_gain2);
        if (!isset($request->date)) {
            $gain_array2 = json_encode($data_gain);
        }
        $active_gains = [];
        if ($gain_incentive_first) {
            $active_gains = GainIncentive::where('sgk_company_id', $sgk_company->id)->where('accrual', $gain_incentive_first->accrual)->get();
        }

        // pass view file
        $title = mb_strtoupper(  $sgk_company->name.'-'.getFullMonthName($gain_incentive_first->accrual->format('M'))).' / '.date("Y"). '-HAKEDİŞ-RAPORU';


        $empty = true;
        $sgk_company2 = ApprovedIncentive::where('sgk_company_id',$sgk_company->id)->where('law_no',27256)->orderBy('accrual','ASC')->get();
        $gain_reports = GainReport::all();
        foreach ($gain_reports as  $report)
        {
            $gains[] = array('0'=>$report->year,'1'=>$report->gain_money);
        }

        $pdf = PDF::loadView('pdfs.test', compact('data_gain_total', 'gain_incentives', 'sgk_company', 'data_gain2', 'gain_array', 'active_gains', 'gain_incentive_first', 'data_gain', 'title', 'data_for_pdf_array','text_fatura', 'empty', 'all', 'company','sgk_company2','gains'));
        //return view('pdfs.test', compact('data_gain_total', 'gain_incentives', 'sgk_company', 'gain_array', 'active_gains', 'gain_incentive_first', 'data_gain', 'title', 'data_for_pdf_array','empty', 'all', 'company'));

        $header = View::make('pdfs.header', ['title' => $title ])->render();
        $footer =  View::make('pdfs.footer')->render();
        $pdf->setOption('enable-javascript', true);
        $pdf->setOption('images', true);
        $pdf->setOption('header-html', $header);
        $pdf->setOption('footer-html', $footer);
        $pdf->setOption('margin-top', 20);
        $pdf->setOption('margin-right', 6);
        $pdf->setOption('margin-left', 6);
        $pdf->setOption('margin-bottom', 20);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('lowquality', false);
        $pdf->setOption('no-stop-slow-scripts', true);

        $pdf->setOption('enable-smart-shrinking', true);

        //return $pdf->stream();

        return $pdf->download($title.'.pdf');
    }


    public function allIncentivesDetailed ($id)
    {




        $all = true;
        $sgk_company = SgkCompany::find($id);
        $company = Company::find($sgk_company->company_id);
        $sgk_company_ids = SgkCompany::where('company_id', $sgk_company->company_id)->pluck('id');

        $text_fatura = [];
        $data_gain = [];
        $data_gain_total = [];

        $v_6111 = 0;
        $v_27103 = 0;
        $v_5510 = 0;
        $v_7252 = 0;
        $v_14857 = 0;
        $v_total = 0;
        $gain_incentive_first = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->orderBy('accrual', 'DESC')->select('accrual')->first();

        $gain_incentives_for_groups = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->orderBy('accrual', 'DESC')->groupBy('accrual')->selectRaw('sum(law_6111) as total_law_6111, sum(law_27103) as total_law_27103, sum(law_7252) as total_law_7252, accrual')->get();
        $text = '["Tarih", "6111", "27103", "7252"],';
        foreach($gain_incentives_for_groups as $gain_incentives_for_group) {
            $data_gain[] = [
                'tarih' => getFullMonthName($gain_incentives_for_group->accrual->format('M')).'/'.$gain_incentives_for_group->accrual->format('Y'),
                '6111' => $gain_incentives_for_group->total_law_6111,
                '7252' => $gain_incentives_for_group->total_law_7252,
                '27103' => $gain_incentives_for_group->total_law_27103
            ];
            if ($gain_incentives_for_group->total_law_6111 > 0) {
                $one = $gain_incentives_for_group->total_law_6111;
            } else {
                $one = 0;
            }
            if ($gain_incentives_for_group->total_law_27103 > 0) {
                $two = $gain_incentives_for_group->total_law_27103;
            } else {
                $two = 0;
            }

            if ($gain_incentives_for_group->total_law_7252 > 0) {
                $three = $gain_incentives_for_group->total_law_7252;
            } else {
                $three = 0;
            }
            $text = $text."['".getFullMonthName($gain_incentives_for_group->accrual->format('M'))."/".$gain_incentives_for_group->accrual->format('Y')."',".$one.", ".$two.", ".$three."],";

        }
        $gain_incentives = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->orderBy('accrual', 'DESC')->get();
        foreach($gain_incentives as $gain_incentive) {
            $v_6111 += $gain_incentive->law_6111;
            $v_5510 += $gain_incentive->law_5510;
            $v_27103 += $gain_incentive->law_27103;
            $v_14857 += $gain_incentive->law_14857;
            $v_7252 += $gain_incentive->law_7252;
            $v_total += $gain_incentive->total_amount;


        }

        $data_gain_total[] = [
            'tarih' => getFullMonthName($gain_incentive_first->accrual->format('M')),
            '6111' => $v_6111,
            '27103' => $v_27103,
            '5510' => $v_5510,
            '7252' => $v_7252,
            '14857' => $v_14857,
            'total' => $v_total
        ];
        $text = remove_last_string($text, ',');
       $data_for_pdf_array = $text;
        $data_gain2 = $data_gain;
        $gain_array = json_encode($data_gain);
        $active_gains = [];
        if ($gain_incentive_first) {
            $active_gains = GainIncentive::whereIn('sgk_company_id', $sgk_company_ids)->where('accrual', $gain_incentive_first->accrual)->get();
        }

        // pass view file
        $title = mb_strtoupper( $sgk_company->company->name.'-'.getFullMonthName($gain_incentive_first->accrual->format('M'))).' / '.date("Y"). '-HAKEDİŞ-RAPORU';


        $empty = true;
        foreach ($sgk_company_ids as $id)
        {
            $sgk_companies = ApprovedIncentive::where('sgk_company_id',$id)->where('law_no',27256)->orderBy('accrual','ASC')->get();
            foreach ($sgk_companies as $company)
            {

                if (!empty($company))
                {
                    $totals[] = array('id' => $company->sgk_company_id, 'date' => $company['accrual'] , 'gün'=> $company['total_day']);

                }
            }
        }

            if (isset($totals))
            {
                $filters = [] ;

                foreach ($totals as $total)
                {

                 //  $tarih =  explode('-' ,$total['date']);
                    if (isset($filters[$total['date']]))
                    {

                        $b = $filters[$total['date']]['total_day'] + $total['gün'];
                        unset($filters[$total['date']]);
                        $filters[$total['date']] = array('accrual'=>$total['date'],'total_day'=> $b);
                    }
                    else
                        {
                            $filters[$total['date']] = array('accrual'=>$total['date'],'total_day'=> $total['gün']);

                        }
                }
            }

        $sgk_company2 = !empty($filters) ? $filters : null;
        $gain_reports = GainReport::all();
        foreach ($gain_reports as  $report)
        {
            $gains[] = array('0'=>$report->year,'1'=>$report->gain_money);
        }
        $pdf = PDF::loadView('pdfs.test', compact('data_gain_total', 'gain_incentives', 'data_gain2', 'sgk_company', 'gain_array', 'active_gains', 'gain_incentive_first', 'data_gain', 'title', 'data_for_pdf_array','text_fatura', 'empty', 'all', 'company','sgk_company2','gains'));
        //return view('pdfs.test', compact('data_gain_total', 'gain_incentives', 'sgk_company', 'gain_array', 'active_gains', 'gain_incentive_first', 'data_gain', 'title', 'data_for_pdf_array','empty', 'all', 'company'));

        $header = View::make('pdfs.header', ['title' => $title ])->render();
        $footer =  View::make('pdfs.footer')->render();
        $pdf->setOption('enable-javascript', true);
        $pdf->setOption('images', true);
        $pdf->setOption('header-html', $header);
        $pdf->setOption('footer-html', $footer);
        $pdf->setOption('margin-top', 20);
        $pdf->setOption('margin-right', 6);
        $pdf->setOption('margin-left', 6);
        $pdf->setOption('margin-bottom', 20);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('lowquality', false);
        $pdf->setOption('no-stop-slow-scripts', true);

        $pdf->setOption('enable-smart-shrinking', true);

        //return $pdf->stream();

        return $pdf->download($title.'.pdf');
    }

    public function saveGainImage(Request $request)
    {
        $sgk_company = getSgkCompany();
        $image = $request->base64;
        $type = $request->type;

        $location = "pdf_images/".$sgk_company->id;
        $image_parts = explode(";base64,", $image);

        $image_base64 = base64_decode($image_parts[1]);
        $fileName =  $type.".png";

        $file = $location . '/'.$fileName;

        if (!is_dir($location)) {
            // dir doesn't exist, make it
            mkdir($location);
        }

        $success = file_put_contents($file, $image_base64);
        //$success = Storage::disk('pdf_images')->put($fileName, $image_base64);;
        return $success ? $fileName : 'Unable to save the file.';

    }
    public function imageScreenshot(Request $request)
    {
        $image = $request->image;


        $location = "uploads/";
        $image_parts = explode(";base64,", $image);

        $image_base64 = base64_decode($image_parts[1]);

        $filename = "screenshot_".uniqid().'.png';

        $file = $location . $filename;

        file_put_contents($file, $image_base64);
    }

}
