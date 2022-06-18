<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

 class PerformanceProgramExport implements FromView
{
    private $puans;
    private $zam_oran;
    private $program_types;
    private $employees;
    private $employee_toplam_puan;
    private $company;
    private $company_toplam_puan;
    private $sonuclar;
    private $type_puan;


     public function __construct($puans, $zam_oran,$program_types,$employees,$employee_toplam_puan,$company,$company_toplam_puan,$sonuclar,$type_puan)
     {

         $this->puans = $puans;
         $this->zam_oran = $zam_oran;
         $this->program_types = $program_types;
         $this->employees= $employees;
         $this->employee_toplam_puan= $employee_toplam_puan;
         $this->company = $company;
         $this->company_toplam_puan = $company_toplam_puan;
         $this->sonuclar = $sonuclar;
         $this->type_puan = $type_puan;

     }


    public function view(): View
    {
        return view('performances.programs.reports.excel_reports',['puans' => $this->puans,'zam_oran' => $this->zam_oran,'program_types'=>$this->program_types,'employees'=>$this->employees
        ,'employee_toplam_puan'=>$this->employee_toplam_puan,'company'=>$this->company,'company_toplam_puan'=>$this->company_toplam_puan,'sonuclar'=>$this->sonuclar,'type_puan'=>$this->type_puan]);
    }
}
