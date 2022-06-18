<?php

namespace App\Http\Controllers\Performances;

use App\DataTables\EmployeeDataTable;
use App\Helpers\HashingSlug;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeEditRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\DisciplineService;
use App\Models\Employee;
use App\Models\EmployeeEquivalent;
use App\Models\EmployeeLanguage;
use App\Models\EmployeePersonalInfo;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSubordinate;
use App\Models\ModelHasRole;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceEmployeeDiscipline;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramEvalation;
use App\Models\SgkCompany;
use App\Models\SmsUser;
use App\Models\Statu;
use App\Models\WorkTitle;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    public function sablon()
    {
        return response()->download(public_path().'/sablon/sablon.xlsx');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeDataTable $dataTable)
    {
        session(['any_excel'=>  true]);
       return  $dataTable->render('standards.index');


       // return view('performances.employees.index',compact('employees'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $departments  = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $working_titles = WorkTitle::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $ust_employees = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('first_name','id');
        $pozisyon = Statu::all()->pluck('name','id');
        return view('performances.employees.create',compact('departments','working_titles','sgk_companies','ust_employees','departments','pozisyon'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeCreateRequest $request)
    {

        $control = Employee::where('email',$request->email)->count();
        $control2 = EmployeePersonalInfo::where('identity_number',$request->identity_number)->count();
        $company = SgkCompany::find($request->sgk_company_id);
        $company2 = Company::find($company->company_id);

        if (empty($request->department) || empty($request->work_title))
        {
            return back()->with('danger','Dapartman ve Ünvan Bilgileri Boş Geçilemez');
        }
//
//                 $client = new \SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
//
//             $result = $client->TCKimlikNoDogrula([
//                 'TCKimlikNo' =>  $request->identity_number,
//                 'Ad' => mb_strtoupper($request->first_name),
//                 'Soyad' => mb_strtoupper($request->last_name),
//                 'DogumYili' => date('Y',strtotime(ezey_get_dateformat($request->birth_date, 'toThem')))
//
//             ]);
//
//        if (!$result->TCKimlikNoDogrulaResult) {
//            $request->flash();
//            return back()->with('danger','Kayıt İşlemi Yapılamıyor 3D Security TC KİMLİK NO DOĞRULANAMADI');
//        }


        if ($control == 0 and $control2 == 0)
        {
            $token = md5(uniqid(mt_rand(), true));

            $randomsifre = str_shuffle('0123456789');
            $sifre = substr($randomsifre, 0, 6);

//            $sifre = str_random(8);
            $employee = Employee::create([
                 'company_id'=> $company->company_id,
                 'sgk_company_id' => $request->sgk_company_id,
                 'first_name' => $request->first_name,
                 'last_name' => $request->last_name,
                 'email' => $request->email,
                 'work_email' => $request->email,
                 'work_mobile' => !empty($request->work_mobile) ?  $request->work_mobile : '0',
                 'mobile' => !empty($request->mobile) ?  $request->mobile : '0',
                 'job_start_date' => $request->job_start_date,
                 'job_end_date' => null,
                 'work_type_id' => $request->work_type,
                 'working_title_id' => $request->work_title,
                 'department_id' => $request->department,
                 'address' => $request->address,
                 'pozisyon_id'=>$request->pozisyon,
                 'status' => !empty($request->job_finish_date) ? '0' : '1',
                 'login_password' =>$sifre,
                 'token' => $token

            ]);

                EmployeeSalary::create([
                    'employee_id' => $employee->id,
                    'salary' => $request->salary,
                    'salary_unit' => $request->salary_unit,
                    'salary_validity_date' => strlen($request->salary_validity_date) == 10 ? ezey_get_dateformat($request->salary_validity_date, 'toThem') : null,
                    'salary_period' => $request->salary_period,
                    'salary_type' => intval($request->salary_type),
                    'include_agi' => intval($request->salary_type) == 1 ? $request->include_agi : 0,
                    'active' => $request->salary_active,
                ]);

               EmployeePersonalInfo::create([
                'employee_id' => $employee->id,
                'birth_date' => strlen($request->birth_date) == 10 ? ezey_get_dateformat($request->birth_date, 'toThem') : null,
                'identity_number' => $request->identity_number,
                'marital_status' => $request->marital_status,
                'gender' => $request->gender,
                'disability_level' => $request->disability_level,
                'completed_education_center' => $request->completed_education_center,
                'children_number' => intval($request->children_number),
                'blood_group' => $request->blood_group,
                'home' => $request->home,
                'university' => $request->university,
                'educational_status' => $request->educational_status,
                'completed_education' => $request->completed_education,
            ]);

            if ($employee)
            {
                    $imageHelper = new ImageHelper();
                    $imageHelper->createEmployeeImage($employee);
                    $user = User::where('email',$request->email)->count();


                    if ($user == 0)
                    {

                        if ($company2->is_demo == 1 )
                        {

                            $user = User::create([
                                'name' => $request->first_name.' '.$request->last_name,
                                'employee_id' => $employee->id,
                                'sgk_company_id' => $request->sgk_company_id ,
                                'email' => $request->email ,
                                'password' =>  Hash::make($sifre),
                                'department_id' => $request->department,
                                'is_demo' => 1,
                                'company_id' => $company->company_id,
                            ]);
                        }
                        else
                        {
                            $user = User::create([
                                'name' => $request->first_name.' '.$request->last_name,
                                'employee_id' => $employee->id,
                                'sgk_company_id' => $request->sgk_company_id ,
                                'email' => $request->email ,
                                'password' => Hash::make($request->identity_number),
                                'department_id' => $request->department,
                                'is_demo' => 0,
                                'company_id' => $company->company_id,
                            ]);
                        }


                    }
                    else
                    {
                       $user =  User::where('email',$request->email)->update([
                            'employee_id'=>$employee->id,
                            'sgk_company_id' => $request->sgk_company_id,

                        ]);
                    }

                $user =User::where('email',$request->email)->first();
                // company owner save

                $user->roles()->sync([28]);



                if ($user) {
                    $imageHelper = new ImageHelper();
                    $imageHelper->createUserImage($user);
                    }

                $sms = SmsUser::where('company_id',Auth::user()->company_id)->first();
            if (Auth::user()->company_id !=30)
            {
                if ($sms)
                {

                    $message = $company->name.' Şirketi tarafından ikmetrik sistemine kayıt oluşturulmuştur.Bu sistem sayesinde firmanızın sizler için hazırladığı bordrolarınızı veya Performans Değerlendirme Süreçlerini görüntüleyebilirsiniz. Yazılım içersine giriş yapılabilmesi için Link : '.env('APP_URL').' '.'Kullanıcı Adınız : '.' '.$request->email.' '.'Şifreniz  : TC kimlik numaranız';
                    sendSms(Auth::user()->company_id,$sms,null,$employee,$message);
                }
            }






                return redirect(route('employee.index'))->with('success','Çalışan Başarıyla Eklendi Performans Modülü Kullanılması için  Personelin Üst Ast ve Eşdeğer Atamalarını Yapınız');

            }
            else
            {
                return back()->with('danger','Kayıt İşlemi Başarısız');
            }
        }
        else
        {
            return back()->with('danger','Bu Personel Daha Önce Kayıt Edilmiştir');
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
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Beklenmeyen Bir Hata İle Karşılaşıldı');
        }
        $selectedSgkCompany =  SgkCompany::where('id',$employee->sgk_company_id)->pluck('id')->toArray();
        $departments  = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $working_titles = WorkTitle::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id')->toArray();
        $ust_employees = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('first_name','id');
        $ast_employees = EmployeeSubordinate::where('employee_id',$id)->get()->pluck('subordinate_id')->toArray();
        $employees = Employee::where('company_id',Auth::user()->company_id)->whereNotIn('id',[$id])->get()->pluck('full_name','id');
        $employees2 = Employee::where('company_id',Auth::user()->company_id)->whereNotIn('id',[$id])->get();
        $pozisyon = Statu::all()->pluck('name','id');
        return view('performances.employees.edit',compact('departments','working_titles','sgk_companies','ust_employees','employees','departments','employee','selectedSgkCompany','ast_employees','employees2','pozisyon'));

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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeEditRequest $request, $id)
    {

        $employee = Employee::find($id);
        $control = EmployeePersonalInfo::where('identity_number',$request->identity_number)
            ->whereNotIn('employee_id',[$id])->first();
        if ($control)
        {
            return back()->with('danger','Tc Kimlik No daha önce Kayıt Edilmiştir');
        }

        if (!$employee)
        {
            return back()->with('danger','Beklenmeyen Bir Sorunla Karşılaşıldı');
        }

        if (isset($request->ust_null) and !empty($request->employee_ast_id))
        {
            if (in_array($request->top_manager_id,$request->employee_ast_id))
            {
                return back()->with('danger','Üst Seçilen Biri Ast Olamaz');
            }
        }

        $employee->update([
            'company_id'=> $employee->company_id,
            'sgk_company_id' => $request->sgk_company_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'work_email' => $request->work_email,
            'work_mobile' => !empty($request->work_mobile) ?  $request->work_mobile : ' ',
            'mobile' => !empty($request->mobile) ?  $request->mobile : '0',
            'job_start_date' => $request->job_start_date,
            'job_end_date' => $request->job_finish_date,
            'work_type_id' => $request->work_type,
            'contract_type' => $request->contrack_type,
            'working_title_id' => $request->work_title,
            'department_id' => $request->department,
            'address' => $request->address,
            'work_type' => $request->work_type,
            'pozisyon_id'=>$request->pozisyon,
            'status' => !empty($request->job_finish_date) ? '0' : '1'
        ]);
        $salary_control = EmployeeSalary::where('employee_id',$id)->first();
        if ($salary_control)
        {
            $salary =  EmployeeSalary::where('employee_id',$id)->update([
                'salary' => $request->salary,
                'salary_unit' => $request->salary_unit,
                'salary_validity_date' => strlen($request->salary_validity_date) == 10 ? ezey_get_dateformat($request->salary_validity_date, 'toThem') : null,
                'salary_period' => $request->salary_period,
                'salary_type' => intval($request->salary_type),
                'include_agi' => intval($request->salary_type) == 1 ? $request->include_agi : 0,
                'active' => $request->salary_active,
            ]);
        }
        else
        {
            $salary =  EmployeeSalary::create([
                'employee_id' => $id,
                'salary' => $request->salary,
                'salary_unit' => $request->salary_unit,
                'salary_validity_date' => strlen($request->salary_validity_date) == 10 ? ezey_get_dateformat($request->salary_validity_date, 'toThem') : null,
                'salary_period' => $request->salary_period,
                'salary_type' => intval($request->salary_type),
                'include_agi' => intval($request->salary_type) == 1 ? $request->include_agi : 0,
                'active' => $request->salary_active,
            ]);
        }

        $personel_info_control = EmployeePersonalInfo::where('employee_id',$id)->first();
        if ($personel_info_control)
        {
            $personel_info =  EmployeePersonalInfo::where('employee_id',$id)->update([
                'birth_date' => strlen($request->birth_date) == 10 ? ezey_get_dateformat($request->birth_date, 'toThem') : null,
                'identity_number' => $request->identity_number,
                'marital_status' => $request->marital_status,
                'gender' => $request->gender,
                'disability_level' => $request->disability_level,
                'completed_education_center' => $request->completed_education_center,
                'children_number' => intval($request->children_number),
                'blood_group' => $request->blood_group,
                'educational_status' => $request->educational_status,
                'home' => $request->home,
                'university' => $request->university,
                'completed_education' => $request->completed_education,
            ]);
        }
        else
        {
            $personel_info =  EmployeePersonalInfo::create([
                'employee_id' => $id,
                'birth_date' => strlen($request->birth_date) == 10 ? ezey_get_dateformat($request->birth_date, 'toThem') : null,
                'identity_number' => $request->identity_number,
                'marital_status' => $request->marital_status,
                'gender' => $request->gender,
                'disability_level' => $request->disability_level,
                'completed_education_center' => $request->completed_education_center,
                'children_number' => intval($request->children_number),
                'blood_group' => $request->blood_group,
                'educational_status' => $request->educational_status,
                'home' => $request->home,
                'university' => $request->university,
                'completed_education' => $request->completed_education,
            ]);
        }

            if (isset($request->employee_ast_id)) {
                EmployeeSubordinate::where('employee_id', $id)->delete([]);
                foreach ($request->employee_ast_id as $ast_employee) {
                    EmployeeSubordinate::create([
                        'employee_id' => $id,
                        'subordinate_id' => $ast_employee
                    ]);
                }
            }

         if ($employee or $salary or $personel_info )
         {

             return redirect(route('employee.index'))->with('success','Güncelleme İşlemi Başarılı');
         }
         else
         {
             return back()->with('danger','Güncelleme İşlemi Başarısız');
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
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Personel Bulunamadı');
        }
        Employee::where('id',$id)->delete();
        EmployeePersonalInfo::where('employee_id',$id)->delete([]);
        EmployeeSalary::where('employee_id',$id)->delete([]);
        User::where('employee_id',$id)->delete([]);
        return back()->with('success','Silme İşlemi Başarılı');
    }
    public function employee_disciplines(Request $request)
    {
        if (!$request->file('file'))
        {
            return back()->with('danger','Doysa Yüklenmedi');
        }

        $performance_program = PerformanceProgram::find($request->program_id);

        if (!$performance_program)
        {
            return back()->with('danger','Bu program Bulunamadı');
        }
        $employee = Employee::find($request->employee);
        if (!$employee)
        {
            return back()->with('danger','Çalışan Bulunmadı');
        }

        $file = $request->file('file');
        if ($file) {
            $destinationPath = 'uploads/' . 'disciplines/'.$employee->id;
            $fileName = time() . '-' . $file->getClientOriginalName();
            $fullFilePath = $destinationPath . '/' . $fileName;

            $file->move($destinationPath, $fileName);


        }


        $discipline =  PerformanceEmployeeDiscipline::create([
            'company_id' => Auth::user()->company_id,
            'performance_program_id' => $request->program_id,
            'employee_id' => $request->employee,
            'discipline_date' => $request->date,
            'file'=>$fullFilePath
        ]);

                foreach ($request->disciplines as $key => $cipline)
                {
                    DisciplineService::create([
                        'performance_employee_discipline_id' =>  $discipline->id,
                        'company_id' => Auth::user()->company_id,
                        'sgk_company_id'=> $employee->sgk_company_id,
                        'employee_id'=>$request->employee,
                        'date'=>Date::now(),
                        'discipline_id'=>$key,
                    ]);
                }

        if ($discipline)
        {
            return redirect(route('question_evalation',['type'=>'discipline','id'=>createHashId($request->program_id),'page'=>'1']));
        }
        else
        {
           return back()->with('danger','Kayıt İşlemi Başarısız');
        }
    }
    public function any_employee_store(Request $request)
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        $company = Company::find($sgk_company->company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }

        if (strtolower($request->excel->getClientOriginalExtension()) == "xls") {
            $excel = \SimpleXLS::parse($request->excel);
        } elseif (strtolower($request->excel->getClientOriginalExtension()) == "xlsx") {
            $excel = \SimpleXLSX::parse($request->excel);
        }

        $xls = $excel->rows();

        unset($xls[0]);

        foreach ($xls as $key => $row) {

            $first_name = explode(' ', $row[0]);
            $last_name = explode(' ', $row[1]);

            $department = Department::where('company_id', Auth::user()->company_id)
                ->where('sgk_company_id',$sgk_company_id)
                ->where('name', $row[8])
                ->count();
            if ($department == 0) {
                $department = Department::create([
                    'company_id' => Auth::user()->company_id,
                    'sgk_company_id' => $sgk_company_id,
                    'name' => $row[8]
                ]);
            } else {
                $department = Department::where('company_id', Auth::user()->company_id)
                    ->where('sgk_company_id',$sgk_company_id)
                    ->where('name', $row[8])
                    ->first();
            }

            $working_title = WorkTitle::where('company_id', Auth::user()->company_id)
                ->where('sgk_company_id',$sgk_company_id)
                ->where('name', $row[7])
                ->count();


            if ($working_title == 0) {
                $working_title = WorkTitle::create([
                    'company_id' => Auth::user()->company_id,
                    'sgk_company_id' => $sgk_company_id,
                    'name' => $row[7]
                ]);
            } else {
                $working_title = WorkTitle::where('company_id', Auth::user()->company_id)
                    ->where('sgk_company_id',$sgk_company_id)
                    ->where('name', $row[7])
                    ->first();
            }

            $text = trim($first_name[0]);
            $search = array('Ç', 'ç', 'Ğ', 'ğ', 'ı', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü', ' ');
            $replace = array('c', 'c', 'g', 'g', 'i', 'i', 'o', 'o', 's', 's', 'u', 'u', '-');
            $first_name = str_replace($search, $replace, $text);



            $text = trim($last_name[0]);
            $search = array('Ç', 'ç', 'Ğ', 'ğ', 'ı', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü', ' ');
            $replace = array('c', 'c', 'g', 'g', 'i', 'i', 'o', 'o', 's', 's', 'u', 'u', '-');
            $last_name = str_replace($search, $replace, $text);
            $rand = rand(0,999);
             $emaill = $first_name . '.' . $last_name . $rand . '@'.$sgk_company->domain_name;
             $emaill = mb_strtolower($emaill);
//            $emaill = trim(row[3]);
            $randomsifre = str_shuffle('0123456789');
            $sifre = substr($randomsifre, 0, 6);
//            $random_password = str_random(8);
            $emloyee_control = EmployeePersonalInfo::where('identity_number',trim($row[2]))->count();
            $token = md5(uniqid(mt_rand(), true));


            if ($emloyee_control == 0)
            {

                $employee = Employee::create([
                    'company_id' => Auth::user()->company_id,
                    'sgk_company_id' =>$sgk_company_id,
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $emaill,
                    'work_email' => $emaill,
                    'work_mobile' => trim($row[4]),
                    'mobile' => trim($row[4]),
                    'job_start_date' => '2000-08-03',
//                    'job_start_date' => !empty($row[10]) ?  ezey_get_dateformat($row[10], 'toThem') :  ezey_get_dateformat('2000-08-03', 'toThem'),
                    'job_end_date' => null,
                    'work_type_id' => 1,
                    'contract_type' => 1,
                    'working_title_id' => $working_title->id,
                    'department_id' => $department->id,
                    'address' => 'Bursa',
//                    'address' => !empty(trim($row[11])) ? $row[11] : 'Bursa',
                    'work_type' => 1,
                    'token' => $token,
                    'login_password'=> $sifre,
                    'status' => '1'

                ]);

                EmployeeSalary::create([
                    'employee_id' => $employee->id,
                    'salary' => (float)$row[5],
                    'salary_unit' => 0,
                    'salary_validity_date' => strlen($request->salary_validity_date) == 10 ? ezey_get_dateformat($request->salary_validity_date, 'toThem') : null,
                    'salary_period' => 0,
                    'salary_type' => $row[6],
                    'include_agi' => 0,
                    'active' => 1,
                ]);

                EmployeePersonalInfo::create([
                    'employee_id' => $employee->id,
                    'birth_date' => !empty($row[9]) ? \date('Y-m-d',strtotime($row[9])) :  '1980-01-01' ,
                    'identity_number' => $row[2],
                    'marital_status' => 1,
                    'gender' => 1,
                    'disability_level' => 0,
                    'completed_education_center' => 5,
                    'children_number' => intval(2),
                    'blood_group' => 1,
                    'home' => 1,
                    'university' => 0,
                    'educational_status' => 0,
                    'completed_education' => 5,
                ]);
            }
            else
            {
               $employee_control2 =  EmployeePersonalInfo::where('identity_number',trim($row[2]))->first();


                $employee = Employee::where('id',$employee_control2->id)->update([
                    'company_id' => Auth::user()->company_id,
                    'sgk_company_id' =>$sgk_company_id,
                    'first_name' => $row[0],
                    'last_name' => $row[1],
                    'email' => $emaill,
                    'work_email' => $emaill,
                    'work_mobile' => trim($row[4]),
                    'mobile' => trim($row[4]),
                    'job_start_date' => '2000-08-03',
                    'job_end_date' => null,
                    'work_type_id' => 1,
                    'contract_type' => 1,
                    'working_title_id' => $working_title->id,
                    'department_id' => $department->id,
                    'login_password'=> $randomsifre,
                    'address' => 'Bursa',
                    'work_type' => 1,
                    'status' => '1'

                ]);

                EmployeeSalary::where('employee_id',$employee_control2->id)->update([
                    'employee_id' => $employee_control2->id,
                    'salary' => (float)$row[5],
                    'salary_unit' => 0,
                    'salary_validity_date' => strlen($request->salary_validity_date) == 10 ? ezey_get_dateformat($request->salary_validity_date, 'toThem') : null,
                    'salary_period' => 0,
                    'salary_type' => $row[7],
                    'include_agi' => 0,
                    'active' => 1,
                ]);

                EmployeePersonalInfo::where('employee_id',$employee_control2->id)->update([
                    'employee_id' => $employee_control2->id,
                    'birth_date' => ezey_get_dateformat($row[9], 'toThem'),
                    'identity_number' => $row[2],
                    'marital_status' => 1,
                    'gender' => 1,
                    'disability_level' => 0,
                    'completed_education_center' => 5,
                    'children_number' => intval(2),
                    'blood_group' => 1,
                    'home' => 1,
                    'university' => 0,
                    'educational_status' => 0,
                    'completed_education' => 5,
                ]);
            }

            if (!isset($employee->id))
            {
                $employee = EmployeePersonalInfo::where('identity_number',$row[2])->first();
                $employee = Employee::find($employee->id);
            }

            if ($employee) {

                $imageHelper = new ImageHelper();
                $imageHelper->createAnyEmployeeImage($row, $employee->id);
                $user_control = User::where('email',$employee->email)->get()->count();
                $email = Employee::where('email',trim($employee->email))->count();

                if ($email > 1 )
                {
                    return back()->with('danger','Aynı E-mail Adresi ile Kayıt İşlemi Yapıldı Excel Kontrol Ediniz');
                }
                if ($user_control == 0)
                {
                    $user = User::create([

                        'name' => $first_name . ' ' . $last_name,
                        'employee_id' => $employee->id,
                        'sgk_company_id' => $sgk_company_id,
                        'email' => trim($emaill),
                        'password' =>  Hash::make($row[2]),
                        'department_id' => $department->id,
                        'is_demo' => 0,
                        'company_id' => Auth::user()->company_id,
                    ]);
                }
                else
                {
                    $user = User::where('email',$emaill)->update([
                        'name' => $first_name . ' ' . $last_name,
                        'employee_id' => $employee->id,
                        'sgk_company_id' => $sgk_company_id,
                        'email' => $employee->email,
                        'password' =>Hash::make($row[2]),
                        'department_id' => $department->id,
                        'is_demo' => 0,
                        'company_id' => Auth::user()->company_id,
                    ]);
                }
                if (!isset($user->id))
                {
                    $user = User::where('employee_id',$employee->id)->first();
                }



                // company owner save
                $role = ModelHasRole::where('model_id',$user->id)->where('role_id',28)->first();
                if (!$role)
                {
                    $user->roles()->sync([28]);
                }



                if ($user) {
                    $imageHelper = new ImageHelper();
                    $imageHelper->createUserImage($user);
                }
            }


        }

        return back()->with('success','Kayıt İşlemi Başarılı');
    }
    public function discipline_delete($id)
    {
         $id = HashingSlug::decodeHash($id);
         $control = PerformanceEmployeeDiscipline::find($id);
         if (!$control)
         {
             return back()->with('danger','Kayıt Bulunamadı Teknik Destek Alınız');
         }
         $discipline_services = DisciplineService::where('performance_employee_discipline_id',$control->id);
         $control->delete();
         if ($discipline_services)
         {
             return back()->with('success','Silme İşlemi Başarılı');
         }
         else
         {
             return back()->with('danger','Silme İşlemi Başarısız');
         }
    }
    public function delete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back()->with('danger','Personel Bulunamadı');
        }
        Employee::where('id',$id)->delete();
        EmployeePersonalInfo::where('employee_id',$id)->delete([]);
        EmployeeSalary::where('employee_id',$id)->delete([]);
        User::where('employee_id',$id)->delete([]);
        EmployeeSubordinate::where('employee_id',$id)->delete([]);
        EmployeeSubordinate::where('subordinate_id',$id)->delete([]);
        EmployeeLanguage::where('employee_id',$id)->delete([]);
        EmployeeEquivalent::where('employee_id',$id)->delete();
        PerformanceApplicant::where('employee_id',$id)->delete();
        PerformanceEmployeeDiscipline::where('employee_id',$id)->delete([]);
        PerformanceProgramEvalation::where('employee_id',$id)->delete([]);
        return back()->with('success','Silme İşlemi Başarılı');
    }

    public function profiles($id)
    {

        $id = HashingSlug::decodeHash($id);

        $employee = Employee::find($id);

        return view('performances.employees.show', compact('employee'));
    }


}
