<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\CompanyJobQuestion;
use App\Models\CompanyJobQuestionService;
use App\Models\Department;
use App\Models\DepartmentManager;
use App\Models\Employee;
use App\Models\SgkCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->hasAnyRole('Admin','Performance'))
        {
            $employee = Employee::where('id',Auth::user()->employee_id)->first();
            if (!$employee)
            {
                return back()->with('danger','Çalışan bilgileri Bulunmadı');
            }

            $departments = Department::where('id',$employee->department_id)->get()->pluck('name','id');
            $sgk_companies = SgkCompany::where('id',$employee->sgk_company_id)->get()->pluck('name','id');
            return view('performances.programs.questions.question_settings.polivalans.create',compact('departments','sgk_companies','employee'));

        }
        elseif(Auth::user()->hasAnyRole('department_managers'))
        {
            $employee = Employee::where('id',Auth::user()->employee_id)->first();
            if (!$employee)
            {
                return back()->with('danger','Çalışan bilgileri Bulunmadı');
            }
            $department = DepartmentManager::where('employee_id',Auth::user()->employee_id)->first();
            if (!$department)
            {
                return back()->with('danger','Sizlere Tanımlı Personel Yoktur');
            }

            $departments = Department::where('id',$department->department_id)->get()->pluck('name','id');
            $sgk_companies = SgkCompany::where('id',$employee->sgk_company_id)->get()->pluck('name','id');
            return view('performances.programs.questions.question_settings.polivalans.create',compact('departments','sgk_companies','employee'));

        }
        else
        {
            return back()->with('danger','Sizelere Tanımlı Departman Yoktur');
        }

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = HashingSlug::decodeHash($id);
        $job_question = CompanyJobQuestion::where('id',$id)->first();
        $employee = Employee::where('id',Auth::user()->employee_id)->first();
        if (!$employee)
        {
            return back()->with('danger','Çalışan bilgileri Bulunmadı');
        }
        $departments = Department::where('id',$employee->department_id)->get()->pluck('name','id');
        $sgk_companies = SgkCompany::where('id',$employee->sgk_company_id)->get()->pluck('name','id');
        if (!$job_question)
        {
            return  back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $questions = CompanyJobQuestionService::where('company_job_question_id',$id)->get();

        return view('performances.employees.department_managers.questions.edit',compact('questions','id','job_question','sgk_companies','departments'));

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
}
