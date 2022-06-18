<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PerformansEgitimExport implements FromView
{
    private $datas;
    private $company;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($datas,$company)
    {
        $this->datas = $datas;
        $this->company = $company;
    }


    public function view(): View
    {
        return view('performances.programs.reports.education_excel_table',['datas'=>$this->datas,'company'=>$this->company]);
    }
}
