<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentManager;
use App\Models\Employee;
use App\Models\ModelHasRole;
use App\Models\SgkCompany;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];

        $departments = Department::where('sgk_company_id',$sgk_company_id)->get();
        if (count($departments) <= 0 )
        {
            return back()->with('danger','Şubeye Tanımlı Departman Yoktur');
        }
        return view('performances.employees.department_managers.index',compact('departments'));
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

        $control = DepartmentManager::where('department_id',$id)->first();


        if ($control)
        {
            $control->delete();
            return redirect(route('department-managers.index'))->with('success','Silme İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Silme İşlemi Başarısız');
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
        $id = HashingSlug::decodeHash($id);
        $department = Department::find($id);
        if (!$department)
        {
            return back()->with('danger','Departman Bulunmadı');
        }
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $employees = Employee::where('sgk_company_id',$sgk_company_id)->get()->pluck('full_name','id')->toArray();
        if (count($employees) <=0)
        {
            return back()->with('danger','Personel Listesine Ulaşılamadı.');
        }

        $selectedDepartment = DepartmentManager::where('department_id',$id)->first();



        return view('performances.employees.department_managers.edit',compact('employees','department','selectedDepartment','id'));
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
        $id = HashingSlug::decodeHash($id);
        $department = Department::find($id);
        $employee = Employee::find($request->employees);

        if (!$department or empty($request->employees) or !$employee)
        {
            return back()->with('danger','Güncelleme İşlemi Başarısız');
        }
        $control = DepartmentManager::where('employee_id',$request->employees)->first();

            if ($control)
            {
                $control2 = DepartmentManager::where('employee_id',$request->employees)
                    ->where('department_id',$id)
                    ->first();
                if (!$control2)
                {

                    $departmen = Department::find($control->department_id);
                    return back()->with('danger',$employee->full_name.' '.$departmen->name.' '.'Yönetici Olduğu İçin İşlem Yapılamadı');
                }
            }

        $manager = DepartmentManager::create([
            'department_id'=>$id,
            'employee_id'=>$request->employees
        ]);
             $user = \App\User::where('employee_id',$request->employees)->first();
             $roles = ModelHasRole::where('model_id',$user->id)->get()->pluck('role_id')->toArray();
             if (!in_array(29,$roles))
             {
                 $roles[] = 29;
             }
             $user->roles()->sync($roles);
            if ($manager)
            {
                return redirect(route('department-managers.index'))->with('success','Kayıt İşlemi Başarılı');
            }
            else
            {
                return back()->with('danger','Kayıt İşlemi Başarısız');
            }
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
