<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeEquivalent;
use App\Models\EmployeeSubordinate;
use App\Models\EmployeeTopManager;
use App\Models\PerformanceType;
use App\Models\SgkCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class EmployeeManagerController extends Controller
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
        $control = Employee::find($id);
        if (!$control)
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }

        $employees = Employee::where('company_id',\Auth::user()->company_id)->whereNotIn('id',[$id])->orderBy('first_name')->get();

        $selectedEmployeeAst = EmployeeEquivalent::where('employee_id',$id)->get()->pluck('equivalent_id')->toArray();


        return view('performances.employees.equivalents.edit',compact('employees','selectedEmployeeAst','id','control'));


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
        $control = Employee::find($id);
        if (!$control)
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }

        $managersSql = EmployeeTopManager::where('employee_id',$id)->get();
        $managers = [];
        foreach ($managersSql as $manager)
        {
            if ($manager->type_id == 1)
            {
                $managers['oneManager'] = $manager->manager_id;
            }
            if ($manager->type_id == 2)
            {
                $managers['twoManager'] = $manager->manager_id;
            }

            if ($manager->type_id == 3)
            {
                $managers['thereeManager'] = $manager->manager_id;
            }

        }

        $employees = Employee::where('company_id',\Auth::user()->company_id)->whereNotIn('id',[$id])->orderBy('first_name')->get();
        $employees2 = Employee::where('company_id',\Auth::user()->company_id)->whereNotIn('id',[$id])->orderBy('first_name')->get()->pluck('full_name','id');
        $sgk_companies = SgkCompany::where('company_id',\Auth::user()->company_id)->get()->pluck('name','id');
        $departments = Department::where('company_id',\Auth::user()->company->id)->get()->pluck('name','id');
        $selectedEmployeeUst = Employee::where('id',$id)->first()->pluck('top_manager_id')->toArray();
        $selectedEmployeeAst = EmployeeSubordinate::where('employee_id',$id)->get()->pluck('subordinate_id')->toArray();




        return view('performances.employees.top_managers.edit',compact('managers','employees','sgk_companies','departments','selectedEmployeeAst','selectedEmployeeUst','id','employees2','control'));

    }


    public function employee_subordinate($id)
    {

        $id = HashingSlug::decodeHash($id);
        $control = Employee::find($id);
        if (!$control)
        {
            return back()->with('danger','Beklenmeyen Bir Hatyla Karşılaşıldı');
        }

        $employees = Employee::where('company_id',\Auth::user()->company_id)->whereNotIn('id',[$id])->orderBy('first_name')->get();
        $employees2 = Employee::where('company_id',\Auth::user()->company_id)->whereNotIn('id',[$id])->orderBy('first_name')->get()->pluck('full_name','id');
        $sgk_companies = SgkCompany::where('company_id',\Auth::user()->company_id)->get()->pluck('name','id');
        $departments = Department::where('company_id',\Auth::user()->company->id)->get()->pluck('name','id');
        $selectedEmployeeAst = EmployeeSubordinate::where('employee_id',$id)->get()->pluck('subordinate_id')->toArray();
        return view('performances.employees.subordinates.edit',compact('employees','sgk_companies','departments','selectedEmployeeAst','id','employees2','control'));

    }

    public function employee_supodinate_update(Request $request,$id)
    {
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Beklemeyen Bir Hatayla Karşılaşıldı');
        }

        EmployeeSubordinate::where('employee_id',$id)->delete([]);
        if (isset($request->status))
        {
            foreach ($request->status as $subordinate => $value )
            {
                EmployeeSubordinate::create([
                    'employee_id' =>  $id,
                    'subordinate_id' => $subordinate,
                    'date' => Date::now()
                    ]);
            }

            return redirect(route('employee.index'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir');
        }
        else
        {
            return redirect(route('employee.index'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir '.$employee->full_name.' Bağlı Personel Kalmamıştır');
        }


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
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Şuanda İşleminiz Yapılamamaktadır.');
        }

        if (!isset($request->top_manager_disabled) and !isset($request->top_manager_disabled2) and  $request->top_manager_id == $request->top_manager_id2)
        {
            return back()->with('danger','1.Yönetici ve 2.Yönetici Aynı Kişi Olamaz');
        }
        if(!isset($request->top_manager_disabled2) and !isset($request->top_manager_disabled3) and $request->top_manager_id2 == $request->top_manager_id3)
        {
            return back()->with('danger','2.Yönetici ve 3.Yönetici Aynı Kişi Olamaz');
        }
        if (!isset($request->top_manager_disabled) and !isset($request->top_manager_disabled3) and $request->top_manager_id == $request->top_manager_id3)
        {
            return back()->with('danger','1.Yönetici ve 3.Yönetici Aynı Kişi Olamaz');
        }
        $ust_personel_id = $request->top_manager_id;
        $ust_personel_id2 = $request->top_manager_id2;
        $ust_personel_id3 = $request->top_manager_id3;

        EmployeeTopManager::where('employee_id',$id)->delete([]);

        if (!isset($request->top_manager_disabled))
        {
            $managerControl = EmployeeTopManager::where('employee_id',$id)
                ->where('type_id',1)
                ->count();

            if ($managerControl == 0)
            {
                EmployeeTopManager::create([
                    'company_id'=>Auth::user()->company_id,
                    'employee_id'=>$id,
                    'manager_id'=>$ust_personel_id,
                    'type_id'=>1
                ]);
            }
            else
            {
                EmployeeTopManager::where('employee_id',$id)->update([
                    'manager_id'=>$ust_personel_id
                ]);
            }

            $employee->update([
                'top_manager_id'=>$ust_personel_id,
            ]);

            $control2 = EmployeeSubordinate::where('subordinate_id',$id)->count();


            if ($control2 == 0)
            {
                $ast = EmployeeSubordinate::create([
                    'employee_id' => $ust_personel_id,
                    'subordinate_id' => $id,
                    'date' => Date::now()
                ]);
            }

        }
        else
        {
            $employee->update([
                'top_manager_id'=> null,
            ]);
        }


        if (!isset($request->top_manager_disabled2))
        {
            $managerControl = EmployeeTopManager::where('employee_id',$id)
                ->where('type_id',2)
                ->count();

            if ($managerControl == 0)
            {
                EmployeeTopManager::create([
                    'company_id'=>Auth::user()->company_id,
                    'employee_id'=>$id,
                    'manager_id'=>$ust_personel_id2,
                    'type_id'=>2
                ]);
            }
            else
            {
                EmployeeTopManager::where('employee_id',$id)->update([
                    'manager_id'=>$ust_personel_id2
                ]);
            }
        }

        if (!isset($request->top_manager_disabled3))
        {
            $managerControl = EmployeeTopManager::where('employee_id',$id)
                ->where('type_id',3)
                ->count();

            if ($managerControl == 0)
            {
                EmployeeTopManager::create([
                    'company_id'=>Auth::user()->company_id,
                    'employee_id'=>$id,
                    'manager_id'=>$ust_personel_id3,
                    'type_id'=>3
                ]);
            }
            else
            {
                EmployeeTopManager::where('employee_id',$id)->update([
                    'manager_id'=>$ust_personel_id3
                ]);
            }
        }

        if ($employee)
        {
            return redirect(route('employee.index'))->with('success','Kayıt İşlemi Başarılı');
        }

        else
        {
            return redirect(route('employee.index'))->with('danger','Kayıt İşlemi Başarısız');
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
    public function equivalent_edit(Request $request,$id)
    {
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Beklemeyen Bir Hatayla Karşılaşıldı');
        }


        if (isset($request->status))
        {
            if (count($request->status) > 0)
            {
                EmployeeEquivalent::where('employee_id',$id)->delete([]);
                foreach ($request->status as $key => $statu) {
                    $equivalent = EmployeeEquivalent::create([
                        'employee_id' => $id,
                        'equivalent_id' => $key,
                        'date' => Date::now()
                    ]);
                }
            }
        }

        if ($employee)
        {
            return redirect(route('employee.index'))->with('success','Eş Değerlendirme Personel Atama Kayıt İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Kayıt İşlemi Başarısız');
        }


    }
    public function settings_index($id)
    {
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Çalışan Bulunamadı Destek Talep Edebilirsiniz');
        }
        $performances = PerformanceType::whereIn('id',[1,2,12])->get();
        return view('performances.employees.settings',compact('employee','performances','id'));
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
                    return redirect(route('managers.edit',createHashId($id)));
                }
                break;
            case 'subordinate':
                {
                    return redirect(route('employee_subordinate',['id'=>createHashId($id)]));
                }
                break;
            case 'equivalents':
            {
                return redirect(route('equivalent_show',['id'=>createHashId($id)]));

            }

        }
    }
}
