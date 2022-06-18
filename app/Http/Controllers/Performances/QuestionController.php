<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyDisciplinaryOffenses;
use App\Models\CompanyJobQuestionService;
use App\Models\CompanyQuestion;
use App\Models\Department;
use App\Models\DepartmentManager;
use App\Models\Discontinuty;
use App\Models\Employee;
use App\Models\EmployeeEquivalent;
use App\Models\EmployeeLanguage;
use App\Models\EmployeePersonalInfo;
use App\Models\EmployeeSubordinate;
use App\Models\Performance;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceEvaluation;
use App\Models\PerformanceForm;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramEvalation;
use App\Models\PerformanceProgramType;
use App\Models\PerformanceQuestion;
use App\Models\PerformanceType;
use App\Models\Question;
use App\Models\PerformanceEmployeeDiscipline;
use App\Models\SgkCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Models\CompanyJobQuestion;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole('Performance') or Auth::user()->hasRole('Admin'))
        {
            $performances = PerformanceProgram::where('company_id',\Auth::user()->company_id)->get();
            foreach ($performances as $key => $performance)
            {
                $ilktarih = strtotime(date('Y/m/d'));
                $sontarih = strtotime($performance['target_finish_date']);
                $gunfarki = ($sontarih - $ilktarih)/86400;
                $dayFark[$performance['id']] = $gunfarki;

                if ($gunfarki <= 0)
                {
                    PerformanceProgram::where('company_id',Auth::user()->company_id)
                        ->where('id',$performance['id'])
                        ->update([
                            'status'=>'0'
                        ]);
                    $dayFark[$performance['id']] = 0;
                }

            }
            if (!isset($dayFark))
            {
                $dayFark = [];
            }


            return view('performances.programs.index',compact('performances','dayFark'));
        }

        elseif (Auth::user()->hasRole('Employee'))
        {

            $employe = Employee::find(\Auth::user()->employee_id);
            if (!$employe)
            {
                return back()->with('danger','Böyle bir Çalışan Bulunamadı');
            }

            $programs = PerformanceApplicant::where('employee_id',\Auth::user()->employee_id)->get()->pluck('performance_program_id')->toArray();
            $performance = PerformanceProgram::whereIn('id',$programs)->get()->max('id');
            if (!$performance)
            {
                return back()->with('danger','Size Tanımlı Program Bulunmadı');
            }

            $performance = PerformanceProgram::find($performance)->toArray();

            if (!$performance)
            {
                return back()->with('danger','Tanımlı Bir Program Bulunmamaktadır.');
            }



            $ilktarih = strtotime(date('Y/m/d'));
            $sontarih = strtotime($performance['target_finish_date']);
            $gunfarki = ($sontarih - $ilktarih)/86400;

            if ($gunfarki < 0 )
            {
                return back()->with('danger','Üzgünüz Değerlendirme Süreniz Dolmuştur İk ile Görüşünüz');
            }
            session(['performance_id' => $performance]);
            $id = $performance['id'];

            if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Performance') )
            {
                $performances = PerformanceType::all();
            }
            else
            {
                $performances = PerformanceType::whereIn('id',[1,2,3,10,12])->get();
            }
            return view('performances.programs.questions.invalations.index',compact('performances','id'));

        }

        else
        {
            return back()->with('danger','Bu modül Yetkiniz Yoktur');
        }





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
        $performance = PerformanceProgram::whereIn('id',[$id])->first();

        if (!$performance)
        {
            return back()->with('danger','Program Bulunamadı');
        }

        if ($performance->status == 0)
        {
            return back()->with('danger','Program Tamamlandı Artık Veri Girilemez');
        }
        $performances = PerformanceType::all();
        return view('performances.programs.questions.invalations.index',compact('performances','id'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd($id);
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
    public function question_evalation($type,$id,$page)
    {

        $id = HashingSlug::decodeHash($id);
        $employee = Employee::find(\Auth::user()->employee_id);
        if ($id == 0)
        {
            $performance = true;
        }
        else
        {
            $performance = PerformanceProgram::where('id',$id)->first();
        }

        $performance_type = PerformanceType::where('slug_en',$type)->first();
        if ($type == 'discipline_create')
        {


        }
        else
        {
            $performance_program_type =PerformanceProgramType::where('performance_type_id',$performance_type->id)
                ->where('performance_program_id',$id)
                ->first();
        }




        if (!$performance)
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }

        if (!$employee)
        {
            return back()->with('danger','Çalışan Bulunamadı');
        }

        switch ($type)
        {
            case  'top_manager':
                if (empty($employee->top_manager_id) or $employee->top_manager_id == null or $employee->top_manager_id == 0 )
                {
                    return back()->with('danger','Üzgünüz Size Tanımlı Bir Yönetici Yoktur İk ile Görüşün');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                    ->where('performance_program_id',$id)
                    ->where('evalation_id',$employee->top_manager_id)
                    ->where('type_id',1)
                    ->count();
                if ($control > 0)
                {
                    return back()->with('danger','Üst Yöneticinizi Daha Önce Değerlendirdiniz.');
                }

                $questions = PerformanceQuestion::where('company_question_id',$performance->top_form_id)->get();
                $questions_count = PerformanceQuestion::where('company_question_id',$performance->top_form_id)->count();
                if ($questions_count < 1 )
                {
                    return back()->with('danger','Tanımlı Soru Yoktur');
                }
                $top_puan = 100 / $questions_count;
                $min_puan =$top_puan / 4;
                $puanlar = array('1'=> $min_puan , '2'=> $min_puan * 2,'3'=>$min_puan * 3);
                $top_manager = Employee::find($employee->top_manager_id);
               return view('performances.programs.questions.pages.top_managers.top_manager',compact('questions','top_manager','id','top_puan','puanlar'));
            break;
            case 'subordinate':
                    $control =  EmployeeSubordinate::where('employee_id',Auth::user()->employee_id)->count();
                    if ($control == 0)
                    {
                        return back()->with('danger','Size Ait Ast Personel Yoktur');
                    }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                    $questions = PerformanceQuestion::where('company_question_id',$performance->base_form_id)->get();
                    $questions_count = PerformanceQuestion::where('company_question_id',$performance->base_form_id)->count();
                    if ($questions_count < 1 )
                    {
                        return back()->with('danger','Tanımlı Soru Yoktur');
                    }
                    $top_puan = 100 / $questions_count;
                    $min_puan =$top_puan / 4;
                    $puanlar = array('1'=> $min_puan , '2'=> $min_puan * 2,'3'=>$min_puan * 3);

                    $subordinates2 = EmployeeSubordinate::where('employee_id',Auth::user()->employee_id)->get()->pluck('subordinate_id');


                    foreach ($subordinates2 as $value)
                    {

                        $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                            ->where('performance_program_id',$id)
                            ->where('evalation_id',$value)->count();


                        if ($control == 0)
                        {
                            $info[] = $value;
                        }
                    }

                    if (empty($info))
                    {
                        return redirect(route('questions.index'))->with('warning','Tüm Astlar Değerlendirildi');
                    }

                    $subordinates = Employee::whereIn('id',$info)->get()->pluck('full_name','id');
                return view('performances.programs.questions.pages.subordinate.subordinate',compact('subordinates','id','questions','top_puan','puanlar'));

            break;
            case 'self_evaluations':

                $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                    ->where('type_id',3)
                    ->where('performance_program_id',$id)
                    ->count();

                if ($control > 0)
                {
                    return redirect(route('questions.index'))->with('warning','Öz Değerlendirmeniz Yapılmıştır');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $questions = PerformanceQuestion::where('company_question_id',$performance->base_form_id)->get();
                $questions_count = PerformanceQuestion::where('company_question_id',$performance->base_form_id)->count();
                if ($questions_count < 1 )
                {
                    return back()->with('danger','Tanımlı Soru Yoktur');
                }
                $top_puan = 100 / $questions_count;
                $min_puan =$top_puan / 4;
                $puanlar = array('1'=> $min_puan , '2'=> $min_puan * 2,'3'=>$min_puan * 3);
                return view('performances.programs.questions.pages.self_evaluations.self_evaluations',compact('id','questions','top_puan','puanlar'));
                break;
            case 'discipline':

               if (!Auth::user()->hasRole('Performance'))
               {
                   return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
               }
               if ($id !=0 )
               {
                   if (!$performance_program_type)
                   {
                       return back()->with('danger','Kriter Programa Dahil Değildir');
                   }
               }

                  //  $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                    $subordinates = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
                    $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->get();
                    $company_disciplines = CompanyDisciplinaryOffenses::where('company_id',Auth::user()->company_id)->get();
              return view('performances.programs.questions.pages.disciplines.discipline',compact('disciplines','subordinates','id','company_disciplines'));
                break;
            case 'scholl':
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                $employees = Employee::whereIn('id',$program_applicants)->get();
                return view('performances.programs.questions.pages.scholl.scholl',compact('employees','id','page'));
                break;
            case 'discontinuity':
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                $employees = Employee::whereIn('id',$program_applicants)->get();
                $employees_count = Employee::whereIn('id',$program_applicants)->count();
                foreach ($employees as $key => $employee)
                {

                    $discont = Discontinuty::where('employee_id',$employee->id)->get();

                    if ($discont)
                    {
                        $devamsizlik = Discontinuty::where('employee_id',$employee->id)->get();
                        $discontinuty_count[$key] = count($devamsizlik);
                        $discontinuties[$key][$employee->id] = $discont->toArray();
                        $discont_oran[$key] = ($discontinuty_count[$key] * 100)/26;

                    }
                }

                return view('performances.programs.questions.pages.discontinuities.discontinuity',compact('employees','id','discontinuty_count','discontinuties','discont_oran','employees_count'));
                  break;
            case 'social_life';
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                    $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                    $employees = Employee::whereIn('id',$program_applicants)->get();
                    foreach ($employees as $employee)
                    {
                        if (!isset($employee->employee_personel->disability_level))
                        {
                            return back()->with('danger',$employee->full_name.' '.'Kullanıcının Bilgileri Eksik');
                        }
                    }
                    return view('performances.programs.questions.pages.social_lifes.social_life',compact('employees','id'));
                    break;

            case 'discipline_create':
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }

               // $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                $subordinates = Employee::all()->pluck('full_name','id');
                $disciplines = PerformanceEmployeeDiscipline::where('company_id',Auth::user()->company_id)->get();
                $company_disciplines = CompanyDisciplinaryOffenses::where('company_id',Auth::user()->company_id)->get();
                return view('performances.programs.questions.pages.disciplines.discipline_create',compact('disciplines','subordinates','id','company_disciplines'));
                break;
            case 'seniority':
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                $employees = Employee::whereIn('id',$program_applicants)->get();

                             foreach ($employees as $employee)
                             {
                                 $date_job_start_date = Carbon::parse($employee->job_start_date);
                                 $seniority_data[$employee->id]['kidem_suresi'] = getRemainingTime($date_job_start_date);
                             }
                        return view('performances.programs.questions.pages.seniorities.seniority',compact('employees','seniority_data','id'));
                break;
            case 'language':
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                $employees = Employee::whereIn('id',$program_applicants)->get();

                $employees_count = Employee::whereIn('id',$program_applicants)->count();
                foreach ($employees as $key => $employee)
                {
                    $control = EmployeeLanguage::where('employee_id',$employee->id)->get();

                    if ($control)
                    {
                        $languages[$key] = EmployeeLanguage::whereIn('employee_id',$program_applicants)->get()->toArray();
                        $languages_employee[$key][$employee->id] = $control->toArray();
                        $language_count[$employee->id] =   EmployeeLanguage::where('employee_id',$employee->id)->count();
                    }

                }

                return view('performances.programs.questions.pages.languages.language',compact('employees','languages','id','language_count','employees_count','languages_employee'));
                break;
            case 'job_criteria':
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                  $employees =  EmployeeSubordinate::where('employee_id',Auth::user()->employee_id)
                    ->whereIn('subordinate_id',$program_applicants)->get();
                  if (count($employees) <= 0)
                  {
                      return back()->with('danger','Size Tanımlı Personel Yoktur');
                  }

              //  $employees = Employee::whereIn('id',$program_applicants)->get();
                //$questions = CompanyJobQuestionService::where('sgk_company_id',$department->sgk_company_id)->where('department_id',$department->department_id)->get();
                return view('performances.programs.questions.pages.job_criters.job_criters',compact('id','employees'));
                break;
            case 'management_evaluation':
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }

                $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
                $employees = Employee::whereIn('id',$program_applicants)->get();
                return view('performances.programs.questions.pages.managements.management_evaluation',compact('employees','id'));
                break;
            case 'equivalents':

                $control =  EmployeeEquivalent::where('employee_id',Auth::user()->employee_id)->count();
                if ($control == 0)
                {
                    return back()->with('danger','Size Ait Eş Değer Personel Yoktur');
                }
                if (!$performance_program_type)
                {
                    return back()->with('danger','Kriter Programa Dahil Değildir');
                }
                $questions = PerformanceQuestion::where('company_question_id',$performance->base_form_id)->get();
                $questions_count = PerformanceQuestion::where('company_question_id',$performance->base_form_id)->count();
                if ($questions_count < 1 )
                {
                    return back()->with('danger','Tanımlı Soru Yoktur');
                }

                $top_puan = 100 / $questions_count;
                $min_puan =$top_puan / 4;
                $puanlar = array('1'=> $min_puan , '2'=> $min_puan * 2,'3'=>$min_puan * 3);
                $subordinates2 = EmployeeEquivalent::where('employee_id',Auth::user()->employee_id)->get()->pluck('equivalent_id');

                foreach ($subordinates2 as $value)
                {

                    $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                        ->where('performance_program_id',$id)
                        ->where('evalation_id',$value)->count();

                    if ($control == 0)
                    {
                        $info[] = $value;
                    }
                }
                if (empty($info))
                {
                    return back()->with('danger','Tüm Eş Değerler Değerlendirildi');
                }

                $subordinates = Employee::whereIn('id',$info)->get()->pluck('full_name','id');



                return view('performances.programs.questions.pages.equivalents.equivalent',compact('subordinates','id','questions','top_puan','puanlar'));

                break;
            case 'target':
                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }

                return view('performances.programs.targets.index');


        }
    }

    public function question_evalation_store(Request $request,$id,$person_evaluted,$type)
    {
        if (empty(\Auth::user()->employee_id) or Auth::user()->employee_id == 0)
        {
            return back()->with('danger','Kullanıcı Veri Tabanında Bulunumadı');
        }
        $id = HashingSlug::decodeHash($id);


        $employee = Employee::find(\Auth::user()->employee_id);
        $performance = PerformanceProgram::find($id);


        if (!$employee)
        {
            return back()->with('danger','Böyle Bir Kullanıcı Bulunamadı');
        }
        if (!$performance)
        {
            return back()->with('danger','Böyle Bir Program Bulunamadı');
        }

        $type = PerformanceType::where('slug_en',$type)->first();


        switch ($type->slug_en)
        {
            case 'top_manager':


                $person_evaluted = HashingSlug::decodeHash($person_evaluted);
                $person_evaluted = Employee::find($person_evaluted);
                if (!$person_evaluted)
                {
                    return back()->with('danger','Böyle Bir Üst Yönetici Bulunamadı');
                }
                $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                    ->where('performance_program_id',$id)
                    ->where('evalation_id',$person_evaluted)->count();
                if ($control == 0) {
                    $evalation = PerformanceProgramEvalation::create([
                        'employee_id' => \Auth::user()->employee_id,
                        'performance_program_id' => $id,
                        'evalation_id' => $person_evaluted->id,
                        'type_id' => $type->id,
                        'date' => Date::now()
                    ]);

                    foreach ($request->radio as $value => $question) {

                            PerformanceForm::create([
                                'question_id' => $value,
                                'performance_program_evalation_id' => $evalation->id,
                                'puan' => $question
                            ]);
                    }
                    if ($evalation and $question) {

                        return redirect(route('questions.index'))->with('success', 'Üst Değerlendirme Başarıyla Yapıldı');

                    } else {
                        $evalation->delete([]);
                        $question->delete([]);
                        return redirect(route('questions.index'))->with('danger', 'Kayıt Yapılamadı');
                    }
                }
                else
                {
                    return back()->with('danger','Bu Kullanıcı İçin Değerlendirme Yapılmıştır');
                }
                break;

            case 'subordinate':

                $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                    ->where('performance_program_id',$id)
                    ->where('evalation_id',$request->subordinate)->count();
                if ($control == 0) {


                    $evalation = PerformanceProgramEvalation::create([
                        'employee_id' => Auth::user()->employee_id,
                        'performance_program_id' => $id,
                        'evalation_id' => $request->subordinate,
                        'type_id' => $type->id,
                        'date' => Date::now()
                    ]);

                    foreach ($request->radios as $value => $question)
                    {
                           PerformanceForm::create([
                                'question_id' => $value,
                                'performance_program_evalation_id' => $evalation->id,
                                'puan' => $question
                            ]);
                    }

                    if ($evalation)
                    {
                       // return redirect(route('questions.index'))->with('success', 'Ast Değerlendirme Başarılı');
                        return redirect(route('question_evalation', [ 'type' => $type->slug_en , 'id' => createHashId($id),'page'=>1 ]))->with('success', 'Ast Değerlendirme Başarılı');
                    }
                    else
                    {
                        $evalation->delete([]);
                        $question->delete([]);
                        return redirect(route('questions.index'))->with('danger', 'Kayıt Yapılamadı');
                    }

                }
                else
                {
                    return back()->with('danger','Bu Kullanıcı İçin Değerlendirme Yapılmıştır');
                }
               break;

            case 'self_evaluations':

                $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                    ->where('performance_program_id',$id)
                    ->where('evalation_id',Auth::user()->employee_id)->count();
                if ($control == 0) {


                    $evalation = PerformanceProgramEvalation::create([
                        'employee_id' => \Auth::user()->employee_id,
                        'performance_program_id' => $id,
                        'evalation_id' => \Auth::user()->employee_id,
                        'type_id' => $type->id,
                        'date' => Date::now()
                    ]);

                    foreach ($request->radio as $value => $question) {

                            PerformanceForm::create([
                                'question_id' => $value,
                                'performance_program_evalation_id' => $evalation->id,
                                'puan' => $question
                            ]);


                    }
                    if ($evalation and $question) {
                        return redirect(route('questions.index'))->with('success', 'Öz Değerlendirme Başarıyla Yapıldı');
                        // return redirect(route('questions.index'))->with('success', 'Öz Değerlendirme Başarıyla Yapıldı');
                    } else {
                        $evalation->delete([]);
                        $question->delete([]);
                        return redirect(route('qustion.index'))->with('danger', 'Kayıt Yapılamadı');
                    }
                }
                else
                {
                    return back()->with('danger','Bu Kullanıcı İçin Değerlendirme Yapılmıştır');
                }
                break;
            /*
            case 'discipline':

                if (!Auth::user()->hasRole('Performance'))
                {
                    return back()->with('danger','Bu Bölüme sadece insan kaynakları girebilir');
                }

                break;
                */
            case 'job_criteria':

                $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                    ->where('performance_program_id',$id)
                    ->where('evalation_id',$request->evalation_id)
                    ->where('type_id',$type->id)
                    ->count();

                if ($control == 0) {


                    $evalation = PerformanceProgramEvalation::create([
                        'employee_id' => \Auth::user()->employee_id,
                        'performance_program_id' => $id,
                        'evalation_id' => $request->evalation_id,
                        'type_id' => $type->id,
                        'date' => Date::now()
                    ]);

                    foreach ($request->radios as $value => $question) {
                        PerformanceForm::create([
                            'question_id' => $value,
                            'performance_program_evalation_id' => $evalation->id,
                            'puan' => $question
                        ]);
                    }
                    if ($evalation and $question) {
                        return redirect(route('questions.index'))->with('success', 'Meslek Kriter Formu  Başarıyla Kaydedildi');
                    } else {
                        $evalation->delete([]);
                        $question->delete([]);
                        return redirect(route('qustion.index'))->with('danger', 'Kayıt Yapılamadı');
                    }
                }
                else
                {
                    return back()->with('danger','Bu Kullanıcı İçin Değerlendirme Yapılmıştır');
                }
                break;
            case 'equivalents':
                $control = PerformanceProgramEvalation::where('employee_id',Auth::user()->employee_id)
                    ->where('performance_program_id',$id)
                    ->where('evalation_id',$request->subordinate)->count();
                if ($control == 0) {


                    $evalation = PerformanceProgramEvalation::create([
                        'employee_id' => \Auth::user()->employee_id,
                        'performance_program_id' => $id,
                        'evalation_id' => $request->subordinate,
                        'type_id' => $type->id,
                        'date' => Date::now()
                    ]);

                    foreach ($request->radios as $value => $question) {
                        PerformanceForm::create([
                            'question_id' => $value,
                            'performance_program_evalation_id' => $evalation->id,
                            'puan' => $question
                        ]);
                    }
                    if ($evalation and $question) {
                        return redirect(route('questions.index'))->with('success', 'Değerlendirme Başarıyla Yapıldı');
                    } else {
                        $evalation->delete([]);
                        $question->delete([]);
                        return redirect(route('qustion.index'))->with('danger', 'Kayıt Yapılamadı');
                    }
                }
                else
                {
                    return back()->with('danger','Bu Kullanıcı İçin Değerlendirme Yapılmıştır');
                }
                break;

        }



    }
    public function company_questions_index()
    {

        if (!Auth::user()->hasRole('Admin'))
        {
            if (Auth::user()->hasRole('department_managers') and !Auth::user()->hasRole('Performance'))
            {
                $employee_id =  Auth::user()->employee_id;
                $department = DepartmentManager::where('employee_id',$employee_id)->first();
                if (!$department)
                {
                    return back()->with('danger','Size Tanımlı Department Bulunmadı');
                }
                $employee = Employee::find($employee_id);
                if (!$employee)
                {
                    return back()->with('danger','Kullanıcı Bulunmadı');
                }
                $job_questions = CompanyJobQuestion::where('sgk_company_id',$employee->sgk_company_id)
                    ->where('department_id',$department->department_id)
                    ->get();
                return view('performances.employees.department_managers.questions.index',compact('job_questions','department','employee'));
            }
            else
            {
                $questions = CompanyQuestion::where('company_id',Auth::user()->company_id)->get();
                $job_questions = CompanyJobQuestion::where('company_id',Auth::user()->company_id)->get();

                return view('performances.programs.questions.question_settings.index',compact('questions','job_questions'));
            }
        }
        else
        {
            $questions = CompanyQuestion::where('company_id',Auth::user()->company_id)->get();
            $job_questions = CompanyJobQuestion::where('company_id',Auth::user()->company_id)->get();
            return view('performances.programs.questions.question_settings.index',compact('questions','job_questions'));
        }


    }
    public function company_question_edit($id)
    {

        $id = HashingSlug::decodeHash($id);
        $questions = PerformanceQuestion::where('company_question_id',$id)->get();
        if (!$questions)
        {
            return  back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }

        return view('performances.programs.questions.question_settings.edit',compact('questions','id'));
    }

    public function company_question_update(Request  $request)
    {

        $control = PerformanceQuestion::where('company_question_id',$request->company_question_id)->count();


        if ($control <= 0)
        {
            return back()->with('danger','Böle Bir Soru Yok');
        }
       $sonuc =  100 / count($request->sorular)  ;

       if (is_int($sonuc) == false)
       {
           return response()->json([
               'type' => 'error',
               'message' => 'Soru Sayısı 10 Katları Olmalıdır'
           ]);
       }
       PerformanceQuestion::where('company_question_id',$request->company_question_id)->delete();
        foreach ($request->sorular as $key => $soru)
        {

           $questions =  PerformanceQuestion::create([
                'company_question_id' => $request->company_question_id,
                'question' => $soru[0],
               'grup_type' => $request->questions[$key][0]
            ]);
        }

        if ($questions)
        {
            return response()->json(
                [
                    'type' =>'success',
                    'message' => 'Güncelleme İşlemi Başarılı'
                ]
            );
        }
    }

    public function company_quesion_delete($id)
    {
        $id = HashingSlug::decodeHash($id);

        $control = CompanyQuestion::find($id);
        ;

        if (!$control)
        {
            return back()->with('danger','Beklenmeyen Bir Sorunla Karşılaşıldı');
        }
        $baseForm = PerformanceProgram::where('status','1')->where('company_id',Auth::user()->company_id)->where('base_form_id',$id)->first();
        $topForm = PerformanceProgram::where('status','1')->where('company_id',Auth::user()->company_id)->where('top_form_id',$id)->first();

        if ($baseForm or $topForm )
        {
            return back()->with('danger','Bu Form Devam Eden Programda Kullanılıyor Silinemez ');
        }

        $delete = PerformanceQuestion::where('company_question_id',$id)->delete();
        $delete2 = CompanyQuestion::where('id',$id)->delete();
        if ($delete and $delete2)
        {
            return back()->with('success','Silme İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Silme İşlemi Başarısız Yazılım Destek Ekibi İle Görüşünüz');
        }
    }

    public function company_question_create()
    {
        return view('performances.programs.questions.question_settings.create');
    }

    public function company_question_store(Request $request)
    {

        $sonuc =  100 / count($request->sorular)  ;

        if (is_int($sonuc) == false)
        {
            return response()->json([
                'type' => 'error',
                'message' => 'Soru Sayısı 10 Katları Olmalıdır'
            ]);
        }
        if (empty($request->name))
        {
            return response()->json([
                'type' => 'error',
                'message' => 'İsim Alanı Doldurulmaldır'
            ]);
        }

        $company_questions = CompanyQuestion::create([
            'company_id'=>\Auth::user()->company_id,
            'name'=> $request->name,
            'type'=> $request->question_type,
        ]);



        foreach ($request->sorular as $key => $question)
        {
            $create = PerformanceQuestion::create([
                'company_question_id'=>$company_questions->id,
                'question'=> $question[0],
                'grup_type' => $request->questions[$key][0]
            ]);
        }

        if ($create)
        {
            return response()->json([
                'type'=>'success',
                'message' => 'Kayıt İşlemi başarılı!'
            ]);
        }
        else
        {
           return response()->json([
            'type'=>'error',
            'message' => 'Kayıt İşlemi başarısız!'
        ]);
        }

    }

    public function company_polivalans_create()
    {
        $departments = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        return view('performances.programs.questions.question_settings.polivalans.create',compact('departments','sgk_companies'));
    }
    public function company_polivalans_store(Request $request)
    {


        if (empty($request->sgk_company))
        {
            return \response()->json(
                [
                    'type'=>'error',
                    'message'=> 'Şube Seçimi Yapılamadı'
                ]

            );
        }
        $control = CompanyJobQuestion::where('name',$request->name)
            ->first();

        if ($control)
        {
            return \response()->json(
                [
                    'type'=>'error',
                    'message'=> 'Daha Önce Bu İsimde  Kayıt Edilmiş'
                ]

            );

        }

       $job_question =  CompanyJobQuestion::create([
            'company_id' => Auth::user()->company_id,
            'sgk_company_id' => $request->sgk_company,
            'work_type_id' => $request->question_type,
            'department_id' => $request->department,
            'name' => $request->name,
            'date' => Date::now()
        ]);
        foreach ($request->sorular as $key => $soru)
        {

            if (!empty($soru[0]))
            {
                CompanyJobQuestionService::create([
                    'company_job_question_id' => $job_question->id,
                    'question' =>  $soru[0],
                    'date' => Date::now()
                ]);
            }


        }

        if ($job_question)
        {
            return response()->json([
                'type'=>'success',
                'message'=>'kayıt İşlemi Başarılı'
            ]);
        }
        else
        {
            return response()->json([
                'type'=>'error',
                'message'=>'kayıt İşlemi Başarısız'
            ]);
        }
    }


    public function company_polivalans_question_edit($id)
    {
        $id = HashingSlug::decodeHash($id);
        $job_question = CompanyJobQuestion::where('id',$id)->first();
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $departments = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        if (!$job_question)
        {
            return  back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $questions = CompanyJobQuestionService::where('company_job_question_id',$id)->get();

        return view('performances.programs.questions.question_settings.polivalans.edit',compact('questions','id','job_question','sgk_companies','departments'));
    }

    public function company_polivalans_question_update(Request $request)
    {

        if (empty($request->sgk_company))
        {
            return \response()->json(
                [
                    'type'=>'error',
                    'message'=> 'Şube Seçimi Yapılamadı'
                ]

            );
        }
        $control = CompanyJobQuestion::where('id',$request->id)
            ->first();

        if (!$control)
        {
            return \response()->json(
                [
                    'type'=>'error',
                    'message'=> 'Kayıt Bulunmadı'
                ]

            );
        }

        $job_question =  CompanyJobQuestion::where('id',$request->id)->update([
            'company_id' => Auth::user()->company_id,
            'sgk_company_id' => $request->sgk_company,
            'work_type_id' => $request->question_type,
            'department_id' => $request->department,
            'name' => $request->name,
            'date' => Date::now()
        ]);

        CompanyJobQuestionService::where('company_job_question_id',$request->id)->delete([]);
        foreach ($request->sorular as $key => $soru)
        {
            if (!empty($soru[0]) and !empty($request->department[$key][0]) )
            {
                CompanyJobQuestionService::create([
                    'company_job_question_id' => $request->id,
                    'question' =>  $soru[0],
                    'date' => Date::now()
                ]);
            }


        }

        if ($job_question)
        {
            return response()->json([
                'type'=>'success',
                'message'=>'kayıt İşlemi Başarılı'
            ]);
        }
        else
        {
            return response()->json([
                'type'=>'error',
                'message'=>'kayıt İşlemi Başarısız'
            ]);
        }
    }
    public function company_polivalans_question_delete($id)
    {
        $id = HashingSlug::decodeHash($id);

        $control = CompanyJobQuestion::find($id);
        if (!$control)
        {
            return back()->with('danger','Beklenmeyen Bir Hatayla Karşılaşıldı');
        }
        $control->delete();
        if ($control)
        {
            return back()->with('success','Silme İşlmei Başarılı');
        }
        else
        {
            return back()->with('danger','Silme İşlemi Başarısız');
        }
    }

    public function job_criters_test($id ,$program_id)
    {
        $employee = Employee::find($id);
        if (!$employee)
        {
            return back('danger','Beklenmeyen Bir Sorunla Karşılaşıldı');
        }

        $companyJobQuestion = CompanyJobQuestion::where('department_id',$employee->department_id)
            ->where('company_id',$employee->company_id)
            ->where('work_type_id',$employee->work_type)
            ->first();


        if (!$companyJobQuestion)
        {
            return back()->with('danger','Size Tanımlı Soru Kitapçığı Yoktur Departmanınızın Polivalans Sorularını Tanımlamak İçin Menülerden Performans Sorularına Tıklayınız');
        }
        $questions = CompanyJobQuestionService::where('company_job_question_id',$companyJobQuestion->id)->get();
        $questionscount = CompanyJobQuestionService::where('company_job_question_id',$companyJobQuestion->id)->count();
        if ($questionscount == 0)
        {
            return back()->with('danger','Yöneticiniz İle Görüşün Sorularda Problem Var');
        }


        $tob_puan = 100 / $questionscount;
        $min_puan = $tob_puan / 4;
        $puanlar = array('1'=> $min_puan , '2'=> $min_puan * 2,'3'=>$min_puan * 3);

        return view('performances.programs.questions.pages.job_criters.test',compact('program_id','questions','employee','tob_puan','id','puanlar'));

    }



}
