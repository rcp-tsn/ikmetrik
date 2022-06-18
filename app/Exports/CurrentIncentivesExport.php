<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CurrentIncentivesExport implements FromView
{
    private $incitements;
    private $totalStaff;
    private $company;
    private $incitementDate;
    private $multipleLaw;
    private $totalIncitements;
    private $notDaysInfo;
    private $personelOrtalamaTotal;
    private $incentives_finish;
    private $errors;

    public function __construct($incitements, $totalStaff, $company, $incitementDate, $multipleLaw, $totalIncitements, $notDaysInfo, $personelOrtalamaTotal,$incentives_finish,$errors)
    {
        $this->incitements = $incitements;
        $this->totalStaff = $totalStaff;
        $this->company = $company;
        $this->incitementDate = $incitementDate;
        $this->multipleLaw = $multipleLaw;
        $this->totalIncitements = $totalIncitements;
        $this->notDaysInfo = $notDaysInfo;
        $this->personelOrtalamaTotal = $personelOrtalamaTotal;
        $this->incentives_finish = $incentives_finish;
        $this->errors = $errors;
    }

    public function view(): View
    {

        return view('incentives.current_incentives_table', [
            'incitements' => $this->incitements,
            'totalStaff' => $this->totalStaff,
            'company' => $this->company,
            'incitementDate' => $this->incitementDate,
            'multipleLaw' => $this->multipleLaw,
            'totalIncitements' => $this->totalIncitements,
            'notDaysInfo' => $this->notDaysInfo,
            'personelOrtalamaTotal' => $this->personelOrtalamaTotal,
            'incentives_finish' => $this->incentives_finish,
            'errors' => $this->errors
        ]);
    }
}
