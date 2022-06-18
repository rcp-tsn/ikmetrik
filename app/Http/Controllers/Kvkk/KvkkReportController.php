<?php

namespace App\Http\Controllers\Kvkk;

use App\Exports\EmployeeKvkkExport;
use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyHasDocument;
use App\Models\Employee;
use App\Models\EmployeeFile;
use App\Models\EmployeeFileType;
use App\Models\SgkCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;

class KvkkReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get();
        return view('kvkk.reports.index',compact('sgk_companies'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = HashingSlug::decodeHash($id);
        $say = 0;
        $employees = Employee::where('sgk_company_id',$id)
            ->where('status','1')
            ->get();

        $employees_count = Employee::where('sgk_company_id',$id)
            ->where('status','1')
            ->count();

        $type_id = EmployeeFileType::where('company_id',Auth::user()->company_id)
            ->where('name','KVKK')
            ->first();
        $documentCount = 0;
        if ($employees_count > 0 and $type_id)
        {
            $companyDocuments = CompanyHasDocument::where('sgk_company_id',$id)
                ->get();
            $employeeDocuments = [];
            foreach ($companyDocuments as $company_document)
            {

                if ($company_document->document->document_type_id == 1)
                {
                    $documentCount ++;
                    $employeeDocuments[] = $company_document;
                }
            }
            $deger = $employees_count * $documentCount;

           // $a = WorkingFileAudit::where('company_id',Auth::user()->company_id)->count();
           // $documnet_count = $deger - $a;


            foreach ($employees as $employee)
            {
                foreach ($employeeDocuments as $document)
                {
                        $say =  $say +  EmployeeFile::where('document_id',$document->document_id)
                                ->where('employee_id',$employee->id)
                                ->where('zamane_accept',2)
                                ->count();


                }
            }

            $total = $documentCount * $employees_count;



            if ($total > 0)
            {
                $employe_result = (100*$say)/$total;
               return view('kvkk.reports.show',compact('employe_result','id'));

            }
            else
            {
                $employe_result = 0;
                return view('kvkk.reports.show',compact('employe_result','id'));
            }

        }
        else
        {
            return back()->with('danger','Kayıtlı Çalışan veya Evrak Bulunamadı');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function employeeExcel($id)
    {
        $id = HashingSlug::decodeHash($id);
        $sgk_company = SgkCompany::find($id);
        if (!$sgk_company)
        {
            return back()->with('danger','Şube Bulunamadı');
        }
        $company = Company::find($sgk_company->company_id);
        $employees = Employee::where('sgk_company_id',$id)->get();
        $companyDocuments = CompanyHasDocument::where('sgk_company_id',$id)
            ->get();
        $employeeDocuments = [];
        $documentCount = 0;
        foreach ($companyDocuments as $company_document)
        {

            if ($company_document->document->document_type_id == 1)
            {
                $documentCount ++;
                $employeeDocuments[] = $company_document;
            }
        }
        $employeeDocument = [];
        foreach ($employees as $employee)
        {
            foreach ($employeeDocuments as $document)
            {
                $control = EmployeeFile::where('employee_id',$employee->id)
                    ->where('document_id',$document->document_id)
                    ->first();
               if ($control)
               {
                   if ($control->zamane_accept == 2)
                   {
                       $deger = 'KABUL EDİLDİ';
                   }
                   elseif ($control->zamane_accept == 1)
                   {
                       $deger = 'ONAY BEKLİYOR';
                   }
                   else
                   {
                       $deger = 'REDDEDİLDİ';
                   }
                   $employeeDocument[$employee->id][$document->id] = $deger;
               }
               else
               {
                   $employeeDocument[$employee->id][$document->id] = 'Bu Evrak Tanımlı Değildir';
               }

            }
        }

        return Excel::download(new EmployeeKvkkExport($company, $employees, $employeeDocuments,$employeeDocument), $sgk_company->name . ' - ' . date("Y-m-d") . '.xlsx');

    }
}
