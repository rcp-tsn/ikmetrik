<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use function Symfony\Component\Translation\t;

class PerformancePolivalansExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $datas;
    private $company;
    private $employee;
    public function __construct($datas,$company,$employee)
    {
        $this->datas=$datas;
        $this->company = $company;
        $this->employee = $employee;
    }
    public function collection()
    {
        //
    }

    public function view(): View
    {
       return view('performances.programs.reports.polivalans_excel_table',['datas'=>$this->datas,'company'=>$this->company,'employee'=>$this->employee]);
    }
}
