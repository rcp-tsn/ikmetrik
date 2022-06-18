<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeKvkkExport  implements FromView
{
    private $company;
    private $employees;
    private $companyDocuments;
    private $employeeDocument;



    public function __construct($company, $employees, $companyDocuments,$employeeDocument)
    {
        $this->company = $company;
        $this->employees = $employees;
        $this->companyDocuments = $companyDocuments;
        $this->employeeDocument = $employeeDocument;

    }

    public function view(): View
    {
        return view('kvkk.reports.employee_excel',[
            'company' => $this->company,
            'employees' => $this->employees,
            'companyDocuments' => $this->companyDocuments,
            'employeeDocument' => $this->employeeDocument,

        ]);
    }
}
