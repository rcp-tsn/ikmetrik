<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeSubordinate;
use App\Models\PerformanceType;
use App\Models\SgkCompany;
use Illuminate\Http\Request;

class EmployeeManager extends Controller
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

        $employees = Employee::where('company_id',\Auth::user()->company_id)->get()->pluck('name','id');
        $sgk_companies = SgkCompany::where('company_id',\Auth::user()->company_id)->get()->pluck('name','id');
        $departments = Department::where('company_id',\Auth::user()->company->id)->get()->pluck('name','id');
        $selectedEmployeeUst = Employee::where('id',$id)->first()->pluck('last_name','first_name','top_manager_id');
        $selectedEmployeeAst = EmployeeSubordinate::where('employee_id',$id)->get()->pluck('subordinate_id')->toArray();

        return view('performances.employees.top_managers.edit',compact('employees','sgk_companies','departments','selectedEmployeeAst','selectedEmployeeUst'));
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

    public function employee_manager_settings($id)
    {
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Çalışan Bulunamadı Destek Talep Edebilirsiniz');
        }
        $performances = PerformanceType::whereIn('id',[1,2,12])->get();
        return view('performances.settings',compact('employee','performances'));
    }

    public function settings($type,$id,$page)
    {
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Çalışan Bulunamadı');
        }

        switch ($type)
        {
            case 'top_manager':
            {

            }
            break;
            case 'subordinate':
            {

            }
            break;

        }
    }
}
