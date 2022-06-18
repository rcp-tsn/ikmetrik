<?php

namespace App\Http\Controllers\Declarations;
use App\Http\Controllers\Controller;
use App\Models\GainIncentive;
use App\Models\SgkCompany;


class ReportController extends Controller
{
    public function __construct()
    {

    }

    public function pdfIncentiveReport()
    {
        $sgk_company= getSgkCompany();
        $gains = GainIncentive::where("sgk_company_id", $sgk_company->id)
            ->orderby('accrual', 'DESC')
            ->groupBy('accrual')
        ->get();
        return view('reports.tesvik_hakedisleri', compact('sgk_company', 'gains'));
    }
}
