<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLanguage;
use App\Models\EmployeePersonalInfo;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramType;
use App\Models\SgkCompany;
use App\Models\Statu;
use Illuminate\Http\Request;
use App\Models\CompanyStatu;

class CompanyStatus extends Controller
{
    public function index()
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $employees = Employee::where('sgk_company_id',$sgk_company_id)->get()->pluck('full_name','id');
        $status = CompanyStatu::where('sgk_company_id',$sgk_company_id)->get();

        $basamaks = [1,2,3,4,5,6,7,8,9,10];
        $steps = [];

        $companyStatus = CompanyStatu::where('sgk_company_id',$sgk_company_id)->get()->pluck('step')->toArray();

        foreach ($basamaks as $basamak)
        {
            if(in_array($basamak,$companyStatus))
            {
                $steps = array($basamak=>$basamak);
            }
        }
        return view('performances.companyStatus.index',compact('status','employees','steps'));
    }
    public function create()
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $basamaks = [1,2,3,4,5,6,7,8,9,10];
        $steps = [];

        $companyStatus = CompanyStatu::where('sgk_company_id',$sgk_company_id)->get()->pluck('step')->toArray();

        foreach ($basamaks as $basamak)
        {
            if(!in_array($basamak,$companyStatus))
            {
                $steps[] = $basamak;
            }
        }
        if (count($steps) == 0 or $steps == null)
        {
            return back()->with('danger','Tüm Basamaklar Girilmiştir Güncelleme Bölümünden Değişiklik Yapılabilir');
        }
        $status = Statu::all()->pluck('name','id');
        return view('performances.companyStatus.create',compact('status','steps'));
    }
    public function store(Request $request)
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }

       foreach ($request->status as $step => $statu)
       {
           $statu_id = implode(',',$request->status[$step]);

           $control2 = CompanyStatu::where('sgk_company_id',$sgk_company_id)->where('step',$step)->count();
           if ($control2 == 0 and !empty($request->taban[$step][0]) and !empty($request->tavan[$step][0]) and $request->taban[$step][0] < $request->tavan[$step][0])
           {

               CompanyStatu::create([
                   'sgk_company_id' => $sgk_company_id,
                   'company_id' => \Auth::user()->company_id,
                   'status_id' => $statu_id,
                   'step' =>$step,
                   'taban_maas' => $request->taban[$step][0],
                   'tavan_maas' =>$request->tavan[$step][0]
               ]);
           }

       }


       return redirect(route('status.index'))->with('success','Kayıt İşlemi Başarılı');
    }
    public function edit($id)
    {
        $id = HashingSlug::decodeHash($id);
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $companyStatus = CompanyStatu::find($id);
        if (!$companyStatus)
        {
            return back()->with('danger','Hata Beklenmeyen Bir Hatyla Karşılaşıldı');
        }
        $status = Statu::all()->pluck('name','id');
        $steps = [$companyStatus->step];
        return view('performances.companyStatus.edit',compact('companyStatus','status','steps'));
    }
    public function update(Request $request,$id)
    {
        $id = HashingSlug::decodeHash($id);
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $companyStatus = CompanyStatu::find($id);
        if (!$companyStatus)
        {
            return back()->with('danger','Hata Beklenmeyen Bir Hatyla Karşılaşıldı');
        }
        $statu_id = implode(',',$request->status[$companyStatus->step]);
        if ( !empty($request->taban[$companyStatus->step][0]) and !empty($request->tavan[$companyStatus->step][0]) and $request->taban[$companyStatus->step][0] < $request->tavan[$companyStatus->step][0]) {
            $statu = CompanyStatu::where('id', $id)->update([
                'status_id' => $statu_id,
                'taban_maas' => $request->taban[$companyStatus->step][0],
                'tavan_maas' => $request->tavan[$companyStatus->step][0]
            ]);
        }
        else
        {
            return back()->with('danger','Taban Ücret Tavan Ücretten Büyük Olmalıdır');
        }
        if ($statu)
        {
            return redirect(route('status.index'))->with('success','Kayıt İşlemi başarılı');
        }
        else
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }
    }
    public function typeCreate($id)
    {
        $id = HashingSlug::decodeHash($id);
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $companyStatus = CompanyStatu::find($id);
        if (!$companyStatus)
        {
            return back()->with('danger','Hata Beklenmeyen Bir Hatyla Karşılaşıldı');
        }
        return view('performances.companyStatus.statuTypes.create',compact('companyStatus'));
    }
    public function type_status_update(Request $request,$id)
    {
        $id = HashingSlug::decodeHash($id);
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $companyStatus = CompanyStatu::find($id);
        if (!$companyStatus)
        {
            return back()->with('danger','Hata Beklenmeyen Bir Hatyla Karşılaşıldı');
        }
        $toplam = $request->performance + $request->education + $request->language;
        if ($toplam != 100 )
        {
            return back()->with('danger','AĞIRLIK DEĞERLER TOPLAMI 100 OLMALIDIR');
        }
        $companyStatus->update([
            'performance_puan'=>$request->performance,
            'education_puan'=>$request->education,
            'language_puan'=>$request->language
        ]);

        if ($companyStatus)
        {
            return redirect(route('status.index'))->with('success','İşleminiz Başarıyla Gerçekleştirilmiştir');
        }
        else
        {
            return back()->with('danger','BEKLENMEYEN BİR HATAYLA KARŞILAŞILDI');
        }
    }
    public function statusSorgula($id)
    {

        $datas = [];
        $puans = [];
        $performance = 0;
        $language = 0;
        $education = 0;
        $fark = 1;
        $employee = Employee::find($id);

        if (!$employee)
        {
            return response()->json([
                'type'=>'error',
                'message'=>'Beklenmeyen Bir Hatayla Karşılaşıldı'
            ]);

        }
        $sgk_company_id = session()->get('selectedCompany')['id'];

        $pozisyon = $employee->pozisyon;

        $companyStatus = CompanyStatu::where('status_id',$pozisyon->id)->where('sgk_company_id',$sgk_company_id)->first();

        if (!$companyStatus)
        {
            return response()->json([
                'type'=>'error',
                'message'=>'Beklenmeyen Bir Hatayla Karşılaşıldı'
            ]);
        }
        $datas['min_maas'] = $companyStatus->taban_maas;
        $datas['max_maas'] = $companyStatus->tavan_maas;
        $datas['pozisyon'] = $pozisyon->name;

        $performance_applicant = PerformanceApplicant::where('employee_id',$employee->id)->first();

        if ($performance_applicant)
        {

            $program = PerformanceProgram::find($performance_applicant->performance_program_id);
            if (!$program)
            {
                $performance = 0;
            }
            else
            {
                $program_types = PerformanceProgramType::where('performance_program_id',$program->id)->get();
                foreach ($program_types as $type)
                {
                    $puans[] = (float)$type->performance_type_puan($employee->id,$type->performance_program_id,$type->performance_type_id);
                }

            }

            $performance = array_sum($puans);
            $performance = ($performance * $companyStatus->language_puan)/100;
            $employees_languages = EmployeeLanguage::where('employee_id',$employee->id)->count();
            if (!empty($companyStatus->language_puan))
            {
                if ($employees_languages <=1)
                {
                    $language = (30 * $companyStatus->language_puan)/100;
                    $language = number_format($language,2,',','.');
                }
                if ($employees_languages > 1 and $employees_languages <= 2 )
                {
                    $language = (60 * $companyStatus->language_puan)/100;
                    $language = number_format($language,2,',','.');
                }
                if ($employees_languages > 2)
                {
                    $language = (100 * $companyStatus->language_puan)/100;
                    $language = number_format($language,2,',','.');
                }
            }
            else
            {
                $language =  0;
            }

            $employeeInfo = EmployeePersonalInfo::where('employee_id',$employee->id)->first();

            if (!empty($employeeInfo->completed_education) and !empty($performance_program_type->puan))
            {
                if ($employeeInfo->completed_education == 0 or $employeeInfo->completed_education == 1 )
                {
                    $education = (10 * $companyStatus->education_puan)/100;
                    $education  = number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 2)
                {
                    $education = (30 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 3)
                {
                    $education = (50 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 4)
                {
                    $education = (70 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 5)
                {
                    $education = (90 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 6)
                {
                    $education = (100 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
            }
            else
            {
                $education =  0;
            }
            $education =  str_replace(',','.',$education);
            $toplamPuan = $performance + $language + (float)$education;
            $fark =  $companyStatus->tavan_maas - $companyStatus->taban_maas  ;
            $sonuc = ($fark * $toplamPuan) / 100;
            $datas['sonuc'] = $sonuc;
            $datas['fark'] = $fark;
            $datas['performance2'] =$companyStatus->performance_puan;
            $datas['language2'] = $companyStatus->language_puan;
            $datas['education2'] = $companyStatus->education_puan;
            $datas['performance'] =$performance;
            $datas['language'] = $language;
            $datas['education'] = (double)$education;
            $a = $sonuc + $companyStatus->tavan_maas;
            if ($a > $companyStatus->tavan_maas )
            {
                $sonuc = $companyStatus->tavan_maas;
            }
            return response()->json([
                'type'=>'success',
                'message'=>'İşlem Başarılı',
                'datas'=>$datas
            ]);
        }
        else
        {
            $performance = 0;
            $employees_languages = EmployeeLanguage::where('employee_id',$employee->id)->count();
            if (!empty($companyStatus->language_puan))
            {
                if ($employees_languages <=1)
                {
                    $language = (30 * $companyStatus->language_puan)/100;
                }
                if ($employees_languages > 1 and $employees_languages <= 2 )
                {
                    $language = (60 * $companyStatus->language_puan)/100;
                }
                if ($employees_languages > 2)
                {
                    $language = (100 * $companyStatus->language_puan)/100;
                }
            }
            else
            {
                $language =  0;
            }

            $employeeInfo = EmployeePersonalInfo::where('employee_id',$employee->id)->first();


            if (!empty($employeeInfo->completed_education))
            {
                if ($employeeInfo->completed_education == 0 or $employeeInfo->completed_education == 1 )
                {
                    $education = (10 * $companyStatus->education_puan)/100;
                    $education  = number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 2)
                {
                    $education = (30 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 3)
                {
                    $education = (50 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 4)
                {
                    $education = (70 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 5)
                {
                    $education = (90 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }
                elseif($employeeInfo->completed_education == 6)
                {
                    $education = (100 * $companyStatus->education_puan)/100;
                    $education =  number_format($education,2,',','.');
                }

            }
            else
            {
                $education =  0;
            }
           $education =  str_replace(',','.',$education);


            $toplamPuan = $performance + $language + (float)$education;

            $fark =  $companyStatus->tavan_maas - $companyStatus->taban_maas  ;
            $sonuc = ($fark * $toplamPuan) / 100;
            $datas['sonuc'] = $sonuc;
            $datas['fark'] = $fark;
            $datas['performance2'] =$companyStatus->performance_puan;
            $datas['language2'] = $companyStatus->language_puan;
            $datas['education2'] = $companyStatus->education_puan;
            $datas['performance'] =$performance;
            $datas['language'] = $language;
            $datas['education'] = (double)$education;
            $a = $sonuc + $companyStatus->tavan_maas;
            if ($a > $companyStatus->tavan_maas )
            {
                $sonuc = $companyStatus->tavan_maas;
            }
            return response()->json([
                'type'=>'success',
                'message'=>'İşlem Başarılı',
                'datas'=>$datas
            ]);

        }
    }
    public function statusSorgulaBasamak($id)
    {

        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);
        $datas = [];
        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $companyStatus = CompanyStatu::where('step',$id)->first();

        if (!$companyStatus)
        {
            return back()->with('danger','Hata Beklenmeyen Bir Hatyla Karşılaşıldı');
        }

        $employees = Employee::where('pozisyon_id',$companyStatus->status_id)->where('sgk_company_id',$sgk_company_id)->get();


        foreach ($employees as $employee)
        {
            $datas[] = '<tr><td style="font-weight: bold;font-size: 15px">'.$employee->full_name.'</td><td style="font-weight: bold;font-size: 15px">'.$employee->department->name.'</td><td style="font-weight: bold;font-size: 15px">'.number_format($employee->employeeSalary->salary,2,',','.').'</td></tr>';
        }

        return response()->json([
            'type'=>'success',
            'data'=>$datas,
            'pozisyon' => $companyStatus->statu()
        ]);
    }
}
