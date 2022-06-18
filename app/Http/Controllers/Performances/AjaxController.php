<?php

namespace App\Http\Controllers\Performances;

use App\Exports\PerformansEgitimExport;
use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyJobQuestionService;
use App\Models\CompanyQuestion;
use App\Models\Department;
use App\Models\Discontinuty;
use App\Models\Employee;
use App\Models\EmployeeFileType;
use App\Models\EmployeeInterest;
use App\Models\EmployeeLanguage;
use App\Models\EmployeePersonalInfo;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSubordinate;
use App\Models\ManagementPuan;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceEmployeeDiscipline;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramType;
use App\Models\PerformanceQuestion;
use App\Models\PersonelFileType;
use App\Models\Question;
use App\Models\SgkCompany;
use App\Models\UniversityPart;
use App\Models\WorkTitle;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Excel;

class AjaxController extends Controller
{

    public function employee_filter($sgk_company_id,$department_id)
    {

        $sgk_company = SgkCompany::find($sgk_company_id);
        if (!$sgk_company)
        {
            return \response()->json([
                'type'=>'error',
                'messages'=>'Firma Bulunamadı'
            ]);
        }
        if ($department_id != 0)
        {
            $department = Department::find($department_id);
            if (!$department)
            {
                return \response()->json([
                    'type'=>'error',
                    'message'=>'department Bulunamadı'
                ]);
            }
            $employees = Employee::where('sgk_company_id',$sgk_company_id)
                ->where('department_id',$department_id)
                ->orderBy('first_name')
                ->get();
        }
        else
        {
            $employees = Employee::where('sgk_company_id',$sgk_company_id)->orderBy('first_name')->get();
        }
        $tbody = [];
        if (count($employees) > 0)
        {
            foreach ($employees as $employee)
            {
                $tbody[] = '<tr class="type_applicant"><td><label class="checkbox checkbox-lg">
															<input type="checkbox" style="position: unset" value="'. $employee->id .'"  name="employees[]">
															<span></span></label><td>'.$employee->full_name.'</td><td>'.$employee->department->name.'</td></tr>';
            }
        }
        else
        {
            $tbody = 'Personel Bulunamamıştır';
        }

        return \response()->json([
            'type'=>'success',
            'message'=>'Veri Çekme Başarılı',
            'data'=>$tbody
        ]);


    }


    public function sgk_company_filter($id,$performance_id,$work_type)
    {

        if ($work_type > 0)
        {
            $employees = Employee::where('sgk_company_id', $id)
                ->where('work_type',$work_type)
                ->orderBy('first_name')
                ->get();
        }
        else
        {
            $employees = Employee::where('sgk_company_id', $id)->orderBy('first_name')->get();
        }

        $selectedProgramApplicant = PerformanceApplicant::where('performance_program_id', $performance_id)->get()->pluck('employee_id')->toArray();
        $subordinate = [];
        foreach ($employees as $key => $employee) {

            if (in_array($employee->id,$selectedProgramApplicant)) {

                $checbox[$key] = '<label class="checkbox checkbox-lg">
															<input type="checkbox" value="'. $employee->id .'" checked="checked" name="applicant[]">
															<span></span></label>';
            } else {
                $checbox[$key] = '<label class="checkbox checkbox-lg">
															<input type="checkbox" value="'.$employee->id.'" name="applicant[]">
															<span></span></label>';
            }
            if(!empty($employee->top_manager_id))
            {
                $img[$key] = '<img src="/'.$employee->employee_ust($employee->id).'" title="'.$employee->employee_ust($employee->id,true).'"  class="h-75 align-self-end" alt="ssss">';
            }
            else
            {
                $img[$key] = ' Üst Tanımlı Değildir';
            }
            if(count($employee->employee_subordinate($employee->id)) > 0)
            {


                foreach($employee->employee_subordinate($employee->id) as $value => $employee_ast)
                {
                    $a[] = '<div class="symbol symbol-40 symbol-light-white mr-5"><div class="symbol-label">
                    <img src="/'.$employee_ast->avatar.'" title="'.$employee_ast->first_name.' '.$employee_ast->last_name.'"  class="h-75 align-self-end" alt="ssss">
                                                                                    </div>
                                                                                </div>';
                }
                $subordinate[$key] = implode(',',$a);
                unset($a);


            }
            else
            {
                $subordinate[$key] = ' ';
            }



             $datas[] = '<tr class="type_applicant"><td><label class="checkbox checkbox-rounded">' . $checbox[$key] . '</label> <span></span></label></td><td class="employee">'.$employee->full_name.'</td>
            <td><div class="symbol symbol-40 symbol-light-white mr-5"><div class="symbol-label">'.$img[$key].'</div></div></td>
            <td style="width: 32%" class="employee">' . $subordinate[$key].'</td></tr>';
            unset($subordinate[$key]);
        }

        if (empty($datas))
        {
            $datas[] = '<tr class="type_applicant"><td colspan="4" style="font-size: 15px;font-weight: bold;text-align: center">'."Personel Bulunamadı !!".'</td></tr>';
        }

        return $datas;
    }

    public function sgk_company_department_filter($id,$department_id)
    {

      if (empty($id) or empty($department_id))
      {
          return 'Hatalı İşlem';
      }
            $employees = Employee::where('sgk_company_id',$id)
                ->where('department_id',$department_id)
                ->orderBy('first_name')
                ->get();
      if (count($employees) <= 0)
      {
          return 'Çalışan Bulunmadı';
      }

        $subordinate = [];
        foreach ($employees as $key => $employee) {
            $selectedEmployeeAst = EmployeeSubordinate::where('employee_id',$employee->id)->get()->pluck('subordinate_id')->toArray();

            if (in_array($employee->id,$selectedEmployeeAst)) {

                $checbox[$key] = '<label class="checkbox checkbox-rounded">
															<input type="checkbox"  checked="checked" name="status['. $employee->id .']">
															<span></span></label>';
            } else {
                $checbox[$key] = '<label class="checkbox checkbox-rounded">
															<input type="checkbox"  name="status['. $employee->id.']">
															<span></span></label>';
            }
                $img[$key] = '<div class="symbol symbol-50 symbol-light mt-1"><img src="/'.$employee->avatar.'" title="'.$employee->full_name.'"  class="h-75 align-self-end" alt="ssss"></div>';


            $datas[] = '<tr class="type_applicant"><td><label class="checkbox checkbox-rounded">' . $checbox[$key] . '</label> <span></span></label></td><td class="employee">'.$img[$key].'</td>
            <td>'. $employee->full_name.'</td>
            <td  class="employee">' . $employee->department->name.'</td>
            <td  class="employee">' . $employee->sgk_company->name.'</td>
            </tr>';

        }

        if (empty($datas))
        {
            $datas[] = '<tr class="type_applicant"><td colspan="4" style="font-size: 15px;font-weight: bold;text-align: center">'."Personel Bulunamadı !!".'</td></tr>';
        }

        return $datas;
    }

    public function questions_create(Request $request)
    {

        $control = CompanyQuestion::where('name',$request->name)
            ->where('company_id',Auth::user()->company_id)->first();

        if (empty($control->name))
        {

            $company_questions = CompanyQuestion::create([
                'company_id'=>\Auth::user()->company_id,
                'name'=> $request->name,
                'type'=> $request->sorular[0][0],
            ]);


            foreach ($request->sorular as $question)
            {
                PerformanceQuestion::create([
                    'company_question_id'=>$company_questions->id,
                    'question' => $question[1],
                ]);
            }

            if ($company_questions)
            {
                return \response()->json([
                    'addValue' => "<option value='". $company_questions->id  ."'>" . $request->name. "</option>"
                ]);
            }
        }

        else
        {
            return 'Kayıt Zaten Yapılmış';
        }

    }

    public function discipline(Request $request,$id)
    {
        $performance_program = PerformanceProgram::find($id);

        if (!$performance_program)
        {
            return 'Bu Program Bulunmadı';
        }

       $discipline =  PerformanceEmployeeDiscipline::create([
            'performance_program_id' => $id,
            'company_id' => Auth::user()->company_id,
            'employee_id'=>$request->employee,
            'notification'=>$request->notification,
            'discipline_date'=>$request->date,
        ]);

       if ($discipline)
       {
               $data[] = '<tr>'.' '.'<td><div class="symbol symbol-50 symbol-light mt-1">'.'<img src="/'.$discipline->employee->avatar.'"class="h-75 align-self-end" alt=""></div>'.'</td>'.' '.'<td>'.$discipline->employee->first_name.' '.$discipline->employee->last_name.'</td>'
                   .'<td>'.$discipline->discipline_date->format('d/m/Y').'</td>'.'<td>'.$discipline->notification.'</td>'.'<td></td>'.'</tr>';
                    return $data;
       }
       else
       {
           return 'kayıt işlmei başarısız';
       }

        return view('performances.programs.questions.discipline',compact('disciplines','subordinates','id'));
    }

    public function scholl(Request $request)
    {

        $employee = Employee::find($request->employee_id);

        if (!$employee)
        {
            return 'Kullanıcı Bulunamadı';
        }

        $employee->update([
            'scholl' => $request->scholl,
        ]);
        $personelInfo = EmployeePersonalInfo::where('employee_id',$employee->id)->update([
            'completed_education' => $request->completed_education
        ]);

        if ($employee and $personelInfo)
        {
            return $data[] = array('0'=>$request->scholl,'1'=>$request->completed_education);
        }
        else
        {
            return 'kayıt İşlemi Başarısız';
        }
    }

    public function university($id)
    {
        $options = UniversityPart::where('type',$id)->get();
        if (!$options)
        {
            return 'hata';
        }
        foreach ($options as $option)
        {
            $datas[] = '<option value="'.$option->id.'">'.$option->name.'</option>';
        }

        return \response()->json([
            'options'=>$datas
        ]);
    }

    public function discontinuity_create(Request $request)
    {

        $discontinuty = Discontinuty::create([
            'company_id'=>Auth::user()->company_id,
            'performance_program_id'=>$request->program_id,
            'employee_id'=>$request->employee_id,
            'ceza'=>$request->ceza,
            'date'=>$request->date,
            'time' =>  $request->time,
            'time_type' => $request->time_type,
            'defance'=>$request->defance
        ]);


    }

    public function social_life_update(Request $request)
    {
        $employee = Employee::find($request->employee_id);
        if (!$employee)
        {
            return 'Personel Bulunamadı';
        }

        $personel_info =  EmployeePersonalInfo::where('employee_id',$request->employee_id)->update([
            'home' => $request->home,
            'university'  => $request->university,
            'disability_level'  =>  $request->disability_level,

        ]);

        if ($personel_info)
        {
            return 'Kayıt İşlemi Başarılı';
        }
    }

    public function employee_language(Request $request)
    {

        $control = EmployeeLanguage::where('language',$request->language)->where('level',$request->level)->first();
        $employee = Employee::find($request->employee_id);

        if ($control)
        {

            $control->update([
                'employee_id' => $request->employee_id,
                'company_id' => Auth::user()->company_id,
                'sgk_company_id' => $employee->sgk_company_id,
                'language' => $request->language,
                'level' => $request->level,
                'performance_program_id' => $request->program_id
            ]);
        }
        else
        {

            $control = EmployeeLanguage::create([
                'employee_id' => $request->employee_id,
                'company_id' => Auth::user()->company_id,
                'sgk_company_id' => $employee->sgk_company_id,
                'language' => $request->language,
                'level' => $request->level,
                'performance_program_id' => !empty($request->program_id) ? $request->program_id : null,
            ]);

        }
    }

    public function department(Request $request)
    {


        $rules = [
            'name' => 'required|string|unique:departments,name,NULL,id,company_id,' . Auth::user()->company_id . '|max:255',
        ];


        $messages = array(
            'name.required' => 'Departman alanı gereklidir.',
            'name.string' => 'Departman alanı hatalı.',
            'name.unique' => 'Departman alanı sistemde mevcut.',
        );

        $this->validate($request , $rules, $messages);


        $workingTitle = Department::create([
            'name' =>  mb_strtoupper($request->name, 'utf8'), //çıktı: HARFLERIN TAMAMI BÜYÜLTÜLECEK ,
            'company_id' => Auth::user()->company_id,
            'sgk_company_id'=>$request->sgk_company_id
        ]);



        return response()->json([
            'addType' => 'select',
            'addValue' => '<option value="' . $workingTitle->id . '" selected>' . $workingTitle->name . '</option>',
            'selectorId' => 'working_title_id',
        ]);
    }

    public function working_title(Request $request)
    {


        $rules = [
            'name' => 'required|string|unique:work_titles,name,NULL,id,company_id,' . Auth::user()->company_id . '|max:255',
        ];


        $messages = array(
            'name.required' => 'Ünvan alanı gereklidir.',
            'name.string' => 'Ünvan alanı hatalı.',
            'name.unique' => 'Ünvan alanı sistemde mevcut.',
        );

        $this->validate($request , $rules, $messages);


        $workingTitle = WorkTitle::create([
            'name' =>  mb_strtoupper($request->name, 'utf8'), //çıktı: HARFLERIN TAMAMI BÜYÜLTÜLECEK ,
            'company_id' => Auth::user()->company_id,
            'sgk_company_id'=>$request->sgk_company_id
        ]);



        return response()->json([
            'addType' => 'select',
            'addValue' => '<option value="' . $workingTitle->id . '" selected>' . $workingTitle->name . '</option>',
            'selectorId' => 'working_title_id',
            'sgk_company_id'=>$request->sgk_company_id
        ]);
    }

    public function meslek_create(Request $request)
    {
        // Burası Artık Kullaılmıycak Polivalans OlarakDeğiştirdim QuestionController Kayıt Yaptırıyorum
        if (empty($request->sgk_company_id))
        {
            return \response()->json(
                'Şube Seçimi Yapılamadı'
            );
        }
        foreach ($request->sorular as $department => $soru)
        {

            CompanyJobQuestionService::create([
                'company_id' => Auth::user()->company_id,
                'sgk_company_id' => $request->sgk_company_id,
                'department_id' => $soru[0],
                'question' =>  $soru[1],
                'date' => Date::now()
            ]);
        }
    }

    public function management_puan(Request $request)
    {

        $performance_program = PerformanceProgram::where('id',$request->program_id)->first();
        if (!$performance_program)
        {
            return response()->json([
                'type'=>'error',
                'message'=>'Firmanıza Kayıtlı Program Bulunmadı',
                'page'=>$request->page
            ]);
        }
        $applicant = PerformanceApplicant::where('performance_program_id',$request->program_id)
            ->where('employee_id',$request->employee_id)->first();
        if (!$applicant)


        {
            return response()->json([
                'type'=>'error',
                'message'=>'Beklenmeyen Bir Sorunla Karşılaşıldı',
                'page'=>$request->page
            ]);
        }

        $control = ManagementPuan::where('employee_id',$request->employee_id)
            ->where('performance_program_id',$request->program_id)
            ->where('applicant_id',$applicant->id)
            ->delete();


           $create = ManagementPuan::create([
               'company_id' => Auth::user()->company_id,
               'performance_program_id' => $request->program_id,
               'employee_id' => $request->employee_id,
               'applicant_id' => $applicant->id,
               'puan' => $request->puan,
               'date' => Date::now()
           ]);
           if ($create)
           {
               return response()->json([
                   'type'=>'success',
                   'message'=>'Kayıt İşlemi Başarılı',
                   'page'=>$request->page
           ]);
           }
           else
           {
               return response()->json([
                   'type'=>'error',
                   'message'=>'Kayıt İşlemi Başarısız',
                   'page'=>$request->page
               ]);
           }

    }
    public function zam($id,$min_zam,$max_zam)
    {

        $id = HashingSlug::decodeHash($id);

        $update = PerformanceProgram::where('id', $id)->update([
            'top_zam' => $max_zam,
            'min_zam' => $min_zam
        ]);
        if ($update) {

            $program_id = $id;
            $program = PerformanceProgram::find($program_id);
            $program_types = PerformanceProgramType::where('performance_program_id', $program_id)->get();
            $program_applicants = PerformanceApplicant::where('performance_program_id', $program_id)->get()->pluck('employee_id')->toArray();
            $employees = Employee::whereIn('id', $program_applicants)->get();
            $toplam_puan_Degerlendirme = PerformanceProgramType::where('performance_program_id', $program_id)->sum('puan');

            foreach ($program_types as $type) {
                foreach ($employees as $employee) {

                    $abc = $type->performance_type_puan2($employee->id, $type->performance_program_id, $type->performance_type_id);
                    $employee_toplam_puan[$employee->id][] = $abc;
                    $degerler[$employee->id][] = $abc;
                }

            }

            $zam_oran = array('min_zam' => $program->min_zam, 'max_zam' => $program->top_zam);
            foreach ($employees as $employee) {
                $type_puan[$employee->id] = [];
                if (empty($employee->top_manager_id)) {

                    $i = PerformanceProgramType::where('performance_program_id', $program_id)
                        ->where('performance_type_id', 1)
                        ->first();
                    $type_puan[$employee->id][] = $i->puan;
                }

                $subordinate = EmployeeSubordinate::where('employee_id', $employee->id)->get()->pluck('subordinate_id');
                $employee_subordinate = PerformanceApplicant::where('performance_program_id', $program_id)->whereIn('employee_id', $subordinate)->get()->toArray();
                if (count($employee_subordinate) == 0) {

                    $i = PerformanceProgramType::where('performance_program_id', $program_id)
                        ->where('performance_type_id', 2)
                        ->first();

                    $type_puan[$employee->id][] = $i->puan;


                }

                $toplam_puan_Degerlendirme = (int)$toplam_puan_Degerlendirme;
                $deger = count($type_puan[$employee->id]) > 0 ? array_sum($type_puan[$employee->id]) : 0;
                $degerlendirme_sonuc = $toplam_puan_Degerlendirme - $deger;
                $toplam_puan = array_sum($degerler[$employee->id]);
                $sonuclarr[$employee->id] = ($toplam_puan * 100) / $degerlendirme_sonuc;

                if ($employee->employeeSalary->salary_type == 1) {
                    // $brüt = $employee->employeeSalary->salary / 0.71491;
                    $brut = $employee->employeeSalary->salary;
                    $oran = ($max_zam * $sonuclarr[$employee->id]) / 100;
                    if ($oran < $min_zam) {
                        $oran = $min_zam;
                    }
                    $brut2 = ($brut * $oran) / 100;
                    $sonucc = $brut + $brut2;
                } else {
                    $oran = ($max_zam * $sonuclarr[$employee->id]) / 100;
                    if ($oran < $min_zam) {
                        $oran = $min_zam;
                    }
                    $brut = ($employee->employeeSalary->salary * $oran) / 100;
                    $sonucc = $brut + $employee->employeeSalary->salary;
                }
                $salary_control = EmployeeInterest::where('program_id', $type->performance_program_id)->where('employee_id', $employee->id)->first();
                if (!$salary_control) {
                    $salary = EmployeeInterest::create([
                        'employee_id' => $employee->id,
                        'program_id' => $type->performance_program_id,
                        'back_salary' => $employee->employeeSalary->salary,
                        'new_salary' => $sonucc,
                        'yuzde' => $oran,
                        'date' => \date('Y-m-d'),
                        'type' => 'Performans Program Zammı'
                    ]);
                }


                $save = EmployeeSalary::where('employee_id', $employee->id)->update([
                    'salary' => $sonucc
                ]);
                if ($save)
                {
                    return redirect(route('interest_performance'))->with('success','İşleminiz Başarıyla Gerçekleşmiştir');
                }
                else
                {
                    return redirect(route('interest_performance'))->with('danger','İşleminiz Yapılamamıştır');
                }

            }
        }
    }
    public function educations_filter(Request $request)
       {

           $id = $request->program_id;
           $performance_program = PerformanceProgram::find($id);
           if (!$performance_program)
           {
               return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
           }
           $performance_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
           if (count($performance_applicants) <=0)
           {
               return back()->with('danger','Katılımcı Bulunamadı');
           }
           $employees = Employee::whereIn('id',$performance_applicants)->get();
           if (count($employees) <= 0)
           {
               return back()->with('danger','Çalışan Bulunamadı');
           }
           foreach ($performance_applicants as $applicant)
           {
               $employee = Employee::find($applicant);
               $dates[$applicant] =  education_analiz($employee);
           }
           if ($request->secilenler ==null)
           {
               return back()->with('danger','Hata Seçim Yapılmadı');
           }
           foreach ($employees as $employee)
           {
               if (isset($dates[$employee->id]))
               {
                   foreach($dates[$employee->id] as $education)
                   {
                       if (in_array($education['type'],$request->secilenler))
                       {
                           $educations[$employee->id][] = '<li>'.$education['type'].'</li>';
                       }

                   }
               }

           }
           $datas = [];
           foreach ($employees as $employee)
           {
               if (isset($educations[$employee->id]))
               {
                   $a = implode(' ',$educations[$employee->id]);
               }
               else
               {
                   $a = null;
               }
                if ($a != null)
                {
                    $datas[] = '<tr class="type_applicant education_employee"> <td class="type_applicant">'. $employee->full_name.'</td><td class="type_applicant">'.$performance_program->name.'</td><td class="type_applicant"><ul>'.$a.'</ul></td></tr>';

                }
           }
           return $datas;
       }
    public function educations_excel_report(Request $request)
       {

           if ($request->educations ==null)
           {
               return back()->with('danger','Departman Seçimi Yapılmadı');
           }
           foreach (config('variables.question_grub_type') as $key => $name)
           {
             if (in_array($key,$request->educations))
             {
                 $secilenler[] = $name;
             }

           }

           $id = $request->program_id;
           $performance_program = PerformanceProgram::find($id);

           if (!$performance_program)
           {
               return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
           }
           $performance_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();

           if (count($performance_applicants) <=0)
           {
               return back()->with('danger','Katılımcı Bulunamadı');
           }
           $employees = Employee::whereIn('id',$performance_applicants)->get();

           if (count($employees) <= 0)
           {
               return back()->with('danger','Çalışan Bulunamadı');
           }
           foreach ($performance_applicants as $applicant)
           {
               $employee = Employee::find($applicant);
               $dates[$applicant] =  education_analiz($employee);
           }
           foreach ($employees as $employee)
           {
               if (isset($dates[$employee->id]))
               {
                   foreach($dates[$employee->id] as $education)
                   {

                       if (in_array($education['type'],$secilenler))
                       {
                           $educations[$employee->id][] = $education['type'].',';
                       }

                   }
               }

           }

           $datas = [];
           foreach ($employees as $employee)
           {

               if (isset($educations[$employee->id]))
               {

                       $a = implode(' ',$educations[$employee->id]);


               }
               else
               {
                   $a = null;
               }

               if ($a != null)
               {
                   $datas[] = '<tr class="type_applicant education_employee"> <td class="type_applicant">'. $employee->full_name.'</td><td class="type_applicant">'.$performance_program->name.'</td><td colspan="3" class="type_applicant"><ul>'.$a.'</ul></td></tr>';

               }
           }
            $company = Company::find($performance_program->company_id);
           return Excel::download(new PerformansEgitimExport($datas,$company), 'Performans_Egitim_Raporu' . ' - ' . date("Y-m-d") . '.xlsx');

       }
    public function performance_employee_report(Request $request)
       {

           $performance_types = PerformanceProgramType::where('performance_program_id',$request->performance_id)->orderBy('performance_type_id','ASC')->get();

           $employee = Employee::find($request->employee_id);
            $degerler = '';
           foreach($performance_types as $key => $type) {

               $toplamPuan[] = $type->performance_type_puan2($employee->id, $type->performance_program_id, $type->performance_type_id);
               $degerler = $degerler.'<div class="col-xl-4" >
                                        <div class="card card-custom card-stretch gutter-b" >
                                                <div class="card-body d-flex align-items-center py-0 mt-8" >
                                                    <div class="d-flex flex-column flex-grow-1 py-2 py-lg-5" >
                                                        <label class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 text-hover-primary" >' . $type->performance_type() . '</label >
                                                        <span class="" style = "font-weight: bold;font-size: 16px;color: black" ><span ></span >' . $type->performance_type_puan($employee->id, $type->performance_program_id, $type->performance_type_id) . '</span ><span > puan</span >
                                                    </div>
                                                    <img src = "/icon/' . $type->icon() . '" alt = "" class="align-self-end h-70px" style = "margin-bottom: 20px" >
                                                </div>
                                        </div >
                                    </div >';
               }

          $data = '<div class="modal fade" id="Report'.$employee->id.'" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Performans Değerlendirme Protokolü</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <!--begin::Top-->
                            <div class="d-flex">
                                <!--begin::Pic-->
                                <div class="flex-shrink-0 mr-7">
                                    <div class="symbol symbol-50 symbol-lg-120">
                                        <img alt="'.$employee->full_name.'" src="/'.$employee->avatar.'">
                                    </div>
                                </div>
                                <!--end::Pic-->
                                <!--begin: Info-->
                                <div class="flex-grow-1">
                                    <!--begin::Title-->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                                        <!--begin::User-->
                                        <div class="mr-3">
                                            <!--begin::Name-->
                                            <a href="#" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">'.$employee->full_name.'
                                                <i class="flaticon2-correct text-success icon-md ml-2"></i></a>
                                            <!--end::Name-->
                                            <!--begin::Contacts-->
                                            <div class="d-flex flex-wrap my-2">
                                                <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
															<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
																<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-notification.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24"></rect>
																		<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000"></path>
																		<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5"></circle>
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span>'.$employee->department->name.'</a>
                                                <a href="#" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
															<span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
																<!--begin::Svg Icon | path:assets/media/svg/icons/General/Lock.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<mask fill="white">
																			<use xlink:href="#path-1"></use>
																		</mask>
																		<g></g>
																		<path d="M7,10 L7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 L17,10 L18,10 C19.1045695,10 20,10.8954305 20,12 L20,18 C20,19.1045695 19.1045695,20 18,20 L6,20 C4.8954305,20 4,19.1045695 4,18 L4,12 C4,10.8954305 4.8954305,10 6,10 L7,10 Z M12,5 C10.3431458,5 9,6.34314575 9,8 L9,10 L15,10 L15,8 C15,6.34314575 13.6568542,5 12,5 Z" fill="#000000"></path>
																	</g>
																</svg>
                                                                <!--end::Svg Icon-->
															</span>'.$employee->working_title->name.'</a>
                                            </div>
                                            <!--end::Contacts-->
                                        </div>
                                        <!--begin::User-->
                                    </div>
                                    <!--end::Title-->
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-wrap justify-content-between">
                                        <!--begin::Progress-->
                                        <div class="d-flex ">
                                        <div class="form-group">
                                            <span class="text-dark-50 " style="font-weight: bold">Toplam Puan: </span><span> '.array_sum($toplamPuan).'</span>
                                           </div>
                                        </div>
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Top-->
                            <!--begin::Separator-->
                            <div class="separator separator-solid my-7"></div>
                            <!--end::Separator-->
                            <!--begin::Bottom-->
                            <div class="d-flex align-items-left flex-wrap">'.
                                  $degerler

                            .'</div>
                            <!--end::Bottom-->

                        </div>
                    </div>
                    <!--Kullanıcı Bilgileri son -->
                </div>
            </div>
        </div>
    </div>';

      return $data;
       }
    public function salaryInterest($id)
       {
           $employee = Employee::find($id);
           $datas = [];
           if (!$employee)
           {
               return \response()->json([
                   'type'=>'error',
                   'messages'=>'Çalışan Bulunamadı'
               ]);
           }
           $interests = EmployeeInterest::where('employee_id',$id)->get();
           foreach ($interests as $interest)
           {
               $datas[] = '<tr><td>'.$employee->full_name.'</td><td>'.$interest->date->format('d/m/Y').'</td><td>'.$interest->type.'</td><td>'.$interest->yuzde.'</td><td>'.$interest->back_salary.'</td><td>'.$interest->new_salary.'</td></tr>    ';
           }

           return \response()->json([
               'type'=>'success',
               'data'=>$datas
           ]);
       }

       public function file_type_create(Request $request)
       {
           $rules = [
               'name' => 'required|string|unique:employee_file_types,name,NULL,id,company_id,' . Auth::user()->company_id . '|max:255',
           ];


           $messages = array(
               'name.required' => 'Evrak Türü alanı gereklidir.',
               'name.string' => 'Evrak Türü alanı hatalı.',
               'name.unique' => 'Evrak Türü alanı sistemde mevcut.',
           );
           $this->validate($request , $rules, $messages);

           $name = mb_strtoupper($request->name);
           $control = EmployeeFileType::where('name',$name)->where('company_id',Auth::user()->company_id)->first();
           if($control)
           {
               return response()->json([
                   'name' => 'Daha Önce Kayıt Yapılmış',
               ]);
           }
           $type = EmployeeFileType::create([
               'name' =>  mb_strtoupper($request->name, 'utf8'), //çıktı: HARFLERIN TAMAMI BÜYÜLTÜLECEK ,
               'company_id' => Auth::user()->company_id,
           ]);

           return response()->json([
               'addType' => 'select',
               'addValue' => '<option value="' . $type->id . '" selected>' . $type->name . '</option>',
               'selectorId' => 'personelFileType',
           ]);



       }

}
