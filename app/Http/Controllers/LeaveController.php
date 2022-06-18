<?php

namespace App\Http\Controllers;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Performances\EmployeeManager;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeeLeaveFile;
use App\Models\EmployeeTopManager;
use App\Models\Leave;
use App\Models\SgkCompany;
use Carbon\Carbon;
use App\Models\SmsUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Exception;
use PDF;
use View;


class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sgk_company = getSgkCompany();
        if (Auth::user()->hasRole('Leaves'))
        {

            $employeeLeaves = EmployeeLeave::where('company_id',Auth::user()->company_id)->get();
            return view('leaves.index',compact('employeeLeaves'));
        }
        elseif(Auth::user()->hasRole('Employee'))
        {
            $employee = Employee::find(Auth::user()->employee_id);
            $employeeLeaves = EmployeeLeave::where('company_id',Auth::user()->company_id)->where('employee_id',Auth::user()->employee_id)->get();
            return view('leaves.index',compact('employeeLeaves','employee'));
        }
        else
        {
            return back()->with('danger','Giriş İzniniz Yoktur');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->hasRole('Leaves'))
        {
            $employees = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
            return view('leaves.create',compact('employees'));
        }
        elseif(Auth::user()->hasRole('Employee'))
        {
            $employees = Employee::where('id',Auth::user()->employee_id)->get()->pluck('full_name','id');
            return view('leaves.create',compact('employees'));
        }
        else
        {
            return back()->with('danger','Giriş İzniniz Yoktur');
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

        if ($request->leave_type == 13 or $request->leave_type == 14 or $request->leave_type == 12 )
        {

            if (Auth::user()->hasRole('Leaves')) {
                if ($request->start_time == $request->job_start_time and $request->days == 0) {
                    $request->flash();
                    return back()->with('danger', 'Gün 0 Girilmiştir Saat Farkı olmadığından izin oluşturalamıyor');
                }
                $employee = Employee::find($request->employee);

                if (!$employee) {
                    $request->flash();
                    return back()->with('danger', 'Personel Bilgileri Hatalı.');
                }
                $control = EmployeeLeave::where('employee_id', $request->employee)
                    ->whereIn('status', [1, 2])
                    ->where('start_date', ezey_get_dateformat($request->start_date,'toThem'))
                    ->count();
                if ($control > 0) {
                    $request->flash();
                    return back()->with('danger', 'Kişinin Bu Tarihte İzni Var.');
                }
                try {
                    $employeeLeave = EmployeeLeave::create([
                        'employee_id' => $request->employee,
                        'company_id' => Auth::user()->company_id,
                        'sgk_company_id' => $employee->sgk_company_id,
                        'create_user_id' => Auth::user()->id,
                        'leave_type' => $request->leave_type,
                        'days' => $request->days,
                        'start_date' => ezey_get_dateformat($request->start_date,'toThem'),
                        'start_time' => $request->start_time,
                        'job_start_date' => ezey_get_dateformat($request->job_start_date,'toThem'),
                        'job_start_time' => $request->job_start_time,
                        'description' => $request->description,
                        'status'=>'2'

                    ]);

                    $file = $request->file('files');
                    if ($file) {
                        foreach ($request->files as $files) {
                            foreach ($files as $file) {

                                $destinationPath = 'uploads/' . 'leaves/' . Auth::user()->company_id . '/' . $request->employee;
                                $fileName = time() . '-' . $file->getClientOriginalName();
                                $fullFilePath = $destinationPath . '/' . $fileName;
                                $file->move($destinationPath, $fileName);
                                $leaveFile = EmployeeLeaveFile::create([
                                    'employee_leave_id'=>$employeeLeave->id,
                                    'file'=>$fullFilePath,
                                    'name'=> $file->getClientOriginalName()
                                ]);
                            }

                        }

                    }




                } catch (\Exception $ex) {
                    $request->flash();
                    return back()->with('danger', 'Sistemde Bakım Çalışması Devam Etmektedir Lütfen Daha Sonra Deneyiniz' . $ex->getMessage());
                }

                $managers = EmployeeTopManager::where('employee_id', $request->employee)
                    ->orderBy('type_id', 'ASC')
                    ->get();
                foreach ($managers as $manager) {
                    $user = User::where('employee_id', $manager->manager_id)->first();
                    if ($user->id) {
                        Leave::create([
                            'company_id' => Auth::user()->company_id,
                            'employee_leave_id' => $employeeLeave->id,
                            'employee_id' => $request->employee,
                            'user_id' => $user->id,
                            'status' => '2',
                            'type_authority' => $manager->type_id,
                            'accept_date' => Carbon::now()->timezone('Europe/Istanbul')

                        ]);
                    } else {
                        EmployeeLeave::where('id', $employeeLeave->id)->delete([]);
                        $request->flash();
                        return back()->with('danger', 'Personel Yöneticisinin Bazı Bilgilerine Ulaşılamadı Bundan Dolayı İzin Talebi Oluşamadı');
                    }

                }
                return redirect(route('leaves.index'))->with('success','İzin Başarıyla Kayıt Edilmiştir');
            }
            else {

                if ($request->start_time == $request->job_start_time and $request->days == 0) {
                    $request->flash();
                    return back()->with('danger', 'Gün 0 Girilmiştir Saat Farkı olmadığından izin oluşturalamıyor');
                }
                $employee = Employee::find($request->employee);

                if (!$employee) {
                    $request->flash();
                    return back()->with('danger', 'Personel Bilgileri Hatalı.');
                }
                $control = EmployeeLeave::where('employee_id', $request->employee)
                    ->whereIn('status', [1, 2])
                    ->where('start_date', ezey_get_dateformat($request->start_date,'toThem'))
                    ->count();
                if ($control > 0) {
                    $request->flash();
                    return back()->with('danger', 'Kişinin Bu Tarihte İzni Var.');
                }
                try {
                    $employeeLeave = EmployeeLeave::create([
                        'employee_id' => $request->employee,
                        'company_id' => Auth::user()->company_id,
                        'sgk_company_id' => $employee->sgk_company_id,
                        'create_user_id' => Auth::user()->id,
                        'leave_type' => $request->leave_type,
                        'days' => $request->days,
                        'start_date' => ezey_get_dateformat($request->start_date,'toThem'),
                        'start_time' => $request->start_time,
                        'job_start_date' => ezey_get_dateformat($request->job_start_date,'toThem'),
                        'job_start_time' => $request->job_start_time,
                        'description' => $request->description

                    ]);

                    $file = $request->file('files');
                    if ($file) {
                        foreach ($request->files as $files) {
                            foreach ($files as $file) {

                                $destinationPath = 'uploads/' . 'leaves/' . Auth::user()->company_id . '/' . $request->employee;
                                $fileName = time() . '-' . $file->getClientOriginalName();
                                $fullFilePath = $destinationPath . '/' . $fileName;
                                $file->move($destinationPath, $fileName);
                                $leaveFile = EmployeeLeaveFile::create([
                                    'employee_leave_id'=>$employeeLeave->id,
                                    'file'=>$fullFilePath,
                                    'name'=> $file->getClientOriginalName()
                                ]);
                            }

                        }

                    }
                } catch (\Exception $ex) {
                    $request->flash();
                    return back()->with('danger', 'Sistemde Bakım Çalışması Devam Etmektedir Lütfen Daha Sonra Deneyiniz' . $ex->getMessage());
                }

                $managers = EmployeeTopManager::where('employee_id', $request->employee)
                    ->where('type_id',1)
                    ->get();
                foreach ($managers as $manager) {
                    $user = User::where('employee_id', $manager->manager_id)->first();
                    if ($user->id) {

                        if($manager->type_id == 1)
                        {
                            Leave::create([
                                'company_id' => Auth::user()->company_id,
                                'employee_leave_id' => $employeeLeave->id,
                                'employee_id' => $request->employee,
                                'user_id' => $user->id,
                                'status' => '1',
                                'type_authority' => $manager->type_id,
                            ]);
                            $top_manager = Employee::find($manager->manager_id);
                            $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
                            if($top_manager and $sms)
                            {
                                $loginUrl = env('APP_URL').'/leave/login/'.$top_manager->token.'/'.createHashId($employeeLeave->id);

                                $message =  $employee->full_name.' İsimli Personel '. ezey_get_dateformat($request->start_date,'toThem').' '.$request->start_time.' - '.ezey_get_dateformat($request->job_start_date,'toThem').' '.$request->job_start_time .'Tarihleri Arasında İzin Talebi Oluşturmuştur.İncelemeniz Önemle Rica Olunur. Giriş İçin: '.$loginUrl;
                                sendSms(Auth::user()->company_id,$sms,null,$top_manager,$message);
                            }
                        }
                    } else {
                        EmployeeLeave::where('id', $employeeLeave->id)->delete([]);
                        $request->flash();
                        return back()->with('danger', 'Personel Yöneticisinin Bazı Bilgilerine Ulaşılamadı. Yada Kişinin Üst Yönetici Ataması Yapılmamasından  Dolayı İzin Talebi Oluşamadı');
                    }
                }

                return redirect(route('leaves.index'))->with('success','İzin Talebiniz Alınmış Yönetici Onayına Sunulmuş');
            }
        }
        else
        {
            if ($request->start_time == $request->job_start_time and $request->days == 0) {
                $request->flash();
                return back()->with('danger', 'Gün 0 Girilmiştir Saat Farkı olmadığından izin oluşturalamıyor');
            }
            $employee = Employee::find($request->employee);

            if (!$employee) {
                $request->flash();
                return back()->with('danger', 'Personel Bilgileri Hatalı.');
            }
            $control = EmployeeLeave::where('employee_id', $request->employee)
                ->whereIn('status', [1, 2])
                ->where('start_date', ezey_get_dateformat($request->start_date,'toThem'))
                ->count();


            if ($control > 0) {
                $request->flash();
                return back()->with('danger', 'Kişinin Bu Tarihte İzni Var.');
            }
            try {
                $employeeLeave = EmployeeLeave::create([
                    'employee_id' => $request->employee,
                    'company_id' => Auth::user()->company_id,
                    'sgk_company_id' => $employee->sgk_company_id,
                    'create_user_id' => Auth::user()->id,
                    'leave_type' => $request->leave_type,
                    'days' => $request->days,
                    'start_date' =>ezey_get_dateformat($request->start_date,'toThem'),
                    'start_time' => $request->start_time,
                    'job_start_date' => ezey_get_dateformat($request->job_start_date,'toThem'),
                    'job_start_time' => $request->job_start_time,
                    'description' => $request->description,
                    'status'=>'2'

                ]);

                $file = $request->file('files');
                if ($file) {
                    foreach ($request->files as $files) {
                        foreach ($files as $file) {

                            $destinationPath = 'uploads/' . 'leaves/' . Auth::user()->company_id . '/' . $request->employee;
                            $fileName = time() . '-' . $file->getClientOriginalName();
                            $fullFilePath = $destinationPath . '/' . $fileName;
                            $file->move($destinationPath, $fileName);
                            $leaveFile = EmployeeLeaveFile::create([
                                'employee_leave_id'=>$employeeLeave->id,
                                'file'=>$fullFilePath,
                                'name'=> $file->getClientOriginalName()
                            ]);
                        }

                    }

                }
            } catch (\Exception $ex) {
                $request->flash();
                return back()->with('danger', 'Sistemde Bakım Çalışması Devam Etmektedir Lütfen Daha Sonra Deneyiniz' . $ex->getMessage());
            }

            $managers = EmployeeTopManager::where('employee_id', $request->employee)
                ->orderBy('type_id', 'ASC')
                ->get();

            foreach ($managers as $manager) {

                $user = User::where('employee_id', $manager->manager_id)->first();
                if ($user->id) {
                    Leave::create([
                        'company_id' => Auth::user()->company_id,
                        'employee_leave_id' => $employeeLeave->id,
                        'employee_id' => $request->employee,
                        'user_id' => $user->id,
                        'status' => '2',
                        'type_authority' => $manager->type_id,
                        'accept_date' => Carbon::now()->timezone('Europe/Istanbul')

                    ]);
                } else {
                    EmployeeLeave::where('id', $employeeLeave->id)->delete([]);
                    $request->flash();
                    return back()->with('danger', 'Personel Yöneticisinin Bazı Bilgilerine Ulaşılamadı Bundan Dolayı İzin Talebi Oluşamadı');
                }

            }
            return redirect(route('leaves.index'))->with('success','İzin Başarıyla Kayıt Edilmiştir');
        }

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
        $employeeLeave = EmployeeLeave::find($id);
        if (!$employeeLeave)
        {
            return back()->with('danger','Çalışanların Kayıtları Silinmiştir!');
        }

        $employee = Employee::find($employeeLeave->employee_id);
        if (!$employee)
        {
            return back()->with('danger','Çalışanların Kayıtlarına Ulaşılamadı Sicil Kartından Kontrol Ediniz!');
        }
        $managers = EmployeeTopManager::where('employee_id',$employeeLeave->employee_id)->get();

        $employeeManagers = [];

        foreach ($managers as $manager)
        {
            $employeeManagers[$manager->type_id]['name'] = $manager->user();
        }

        if (!$employeeLeave)
        {
            return back()->with('danger','İzin Detayına Ulaşılamadı');
        }
        $leaveFiles = EmployeeLeaveFile::where('employee_leave_id',$id)->get();
        $leaves = Leave::where('employee_leave_id',$id)->orderBy('type_authority','ASC')
           ->get();

        $sgk_company = SgkCompany::find($employee->sgk_company_id);

        $company = Company::find(Auth::user()->company_id);



        return view('leaves.show',compact('leaves','employeeLeave','employeeManagers','company','sgk_company','leaveFiles'));
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

    public function leaves_status()
    {

        if (Auth::user()->hasRole('Leaves'))
        {
            $leaves = Leave::where('company_id',Auth::user()->company_id)->where('user_id',Auth::user()->id)->get();
            return view('leaves.status.index',compact('leaves'));
        }
        else
        {
            $leaves = Leave::where('user_id',Auth::user()->id)->get();
            return view('leaves.status.index',compact('leaves'));
        }
    }

    public function LeaveLogin($token,$id)
    {
        $password = $token;
        $id = HashingSlug::decodeHash($id);
        $leave = EmployeeLeave::find($id);

        if (!$leave)
        {
            return back()->with('danger','İzin Bulunamadı');
        }

        $employee = Employee::where('token',$password)->first();
        if(!$employee)
        {
            return back()->with('danger','Sistemde Yaşanan Aksaklık Nedeniyle İşleminiz Devam Edemiyor.Lütfen Daha Sonra Tekrar Deneyin.');
        }
        $user = User::where('employee_id',$employee->id)->first();
        if(!$user)
        {
            return back()->with('danger','Sistemde Yaşanan Aksaklık Nedeniyle İşleminiz Devam Edemiyor.Lütfen Daha Sonra Tekrar Deneyin.');
        }

        $login =  Auth::loginUsingId($user->id);

        if($login)
        {
            return redirect(route('leaves_status'));
        }
        else
        {
            return back()->with('danger','Sistemde Yaşanan Aksaklık Nedeniyle İşleminiz Devam Edemiyor.Lütfen Daha Sonra Tekrar Deneyin.');
        }
    }

    public function delete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $employeeLeave = EmployeeLeave::where('id',$id)->delete([]);
        Leave::where('employee_leave_id',$id)->delete([]);
        EmployeeLeaveFile::where('employee_leave_id',$id)->delete([]);
        if ($employeeLeave)
        {
            return back()->with('success','Silme İşlemi Başarılı');

        }
        else
        {
            return back()->with('danger','Silme İşlemi Başarısız');
        }
    }


    public function LeaveFormPdf($id)
    {
        $id = HashingSlug::decodeHash($id);

        $employeeLeave = EmployeeLeave::where('id',$id)->first();
        if (!$employeeLeave)
        {
            return back()->with('danger','Form Oluşturulamadı');
        }
        $employee = Employee::find($employeeLeave->employee_id);
        if (!$employee)
        {
            return back()->with('danger','Form Oluşturulamadı');
        }
        $leaves = Leave::where('employee_leave_id',$id)->orderBy('type_authority','ASC')->get();

        $managers = EmployeeTopManager::where('employee_id',$employeeLeave->employee_id)->get();
        $employeeManagers = [];
        foreach ($managers as $manager)
        {
            $employeeManagers[$manager->type_id]['name'] = $manager->user();
        }
        $company = Company::find(Auth::user()->company_id);
        $sgk_company = SgkCompany::find($employee->sgk_company_id);


        $pdf = PDF::loadView('leaves.pdfs.test',compact('company','leaves','employeeManagers','employeeLeave','sgk_company'));

        return  $pdf->stream();




    }

    public function acceptForm(Request $request,$id)
    {
        $id = HashingSlug::decodeHash($id);
        $employeeLeave = EmployeeLeave::where('id',$id)->first();
        if (!$employeeLeave)
        {
            return back()->with('danger','Döküman şuan yüklenememektedir lütfen daha sonra tekrar deneyiniz.');
        }
        $file = $request->file('files');
        if (!$file)
        {
            return back()->with('danger','Dosya Yükleme Zorunludur');
        }
      $leave =   EmployeeLeave::where('id',$id)->update([
            'file_status' =>'1'
        ]);

        if ($file) {
            foreach ($request->files as $files) {
                foreach ($files as $file) {

                    $destinationPath = 'uploads/' . 'leaves/' . Auth::user()->company_id . '/' . $request->employee;
                    $fileName = time() . '-' . $file->getClientOriginalName();
                    $fullFilePath = $destinationPath . '/' . $fileName;
                    $file->move($destinationPath, $fileName);
                    $leaveFile = EmployeeLeaveFile::create([
                        'employee_leave_id'=>$employeeLeave->id,
                        'file'=>$fullFilePath,
                        'name'=> $file->getClientOriginalName()
                    ]);
                }

            }

        }
        if ($leave)
        {
            return back()->with('success','Dosya Yükleme İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Dosya Yükleme İşlemi Başarısız');
        }





    }
}
