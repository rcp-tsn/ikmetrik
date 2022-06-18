<?php

namespace App\Http\Controllers\Performances;

use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Http\Requests\PerformanceCreateRequest;
use App\Models\Company;
use App\Models\CompanyQuestion;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeInterest;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSubordinate;
use App\Models\MetricReport;
use App\Models\Performance;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceEvaluation;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramType;
use App\Models\PerformanceType;
use App\Models\SgkCompany;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use DateTime;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('performances.index');
        /*
        $performances = PerformanceProgram::where('company_id',\Auth::user()->company_id)->get();
        foreach ($performances as $key => $performances)
        {
            $ilktarih = strtotime(date('Y/m/d'));
            $sontarih = strtotime($performances['finish_date']);
            $gunfarki = ($sontarih - $ilktarih)/86400;
            $dayFark[$performances['id']] = $gunfarki;

        }

        return view('performances.programs.index',compact('performances','dayFark'));
        */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $control = PerformanceProgram::where('company_id',Auth::user()->company_id)->where('status','1')->count();
        if ($control > 0 )
        {
            return back()->with('danger','Devam Eden Programınız Vardır Tamamlandıktan Sonra Yeni Program Açılabilir');
        }
        $employees = Employee::where('company_id',Auth::user()->company_id)->get();
        $employees2 = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $ust_ast_question_forms = CompanyQuestion::where('company_id',Auth::user()->company_id)->where('type',1)->get()->pluck('name','id');
        $ast_ust_question_forms = CompanyQuestion::where('company_id',Auth::user()->company_id)->where('type',2)->get()->pluck('name','id');
        $performance_types = PerformanceType::all();
        $departments = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        return view('performances.programs.create',compact('employees','employees2','sgk_companies','ust_ast_question_forms','ast_ust_question_forms','performance_types','departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    if (!$request->performance_type)
    {
        return back()->with('danger','Performans Kriteri Eklenmedi');
    }
       foreach ($request->performance_type as $key => $type)
       {
           $types[] = $type;
       }

      if (array_sum($types) != 100)
      {
            return back()->with('danger','Performans Türleri Değer Toplamaları 100 Olmalıdır');
      }

      $program_employee = Employee::find(Auth::user()->employee_id);
      $top_form = CompanyQuestion::where('company_id',Auth::user()->company_id)->where('type',1)->first();
      $base_form = CompanyQuestion::where('company_id',Auth::user()->company_id)->where('type',2)->first();

      if (!$top_form or !$base_form)
      {
          return back()->with('danger','Üst Değerlendirme Sorularınız Veya Ast Değerlendirme Sorularınıza Ulaşamadık Performans Sorular Bölümünden Kontrol Ediniz');
      }

      if (empty($request->program_start_date) or empty($request->program_finish_date))
      {
          $finish_date = Carbon::now()->endOfMonth()->toDateString(); // Bu Ayın son günü
          $now_date =  Carbon::now()->startofMonth()->subMonth($request->period)->toDateString(); // Period Döneminin İlk Günü



      }
      else
      {
          $finish_date = $request->program_finish_date;
          $now_date = $request->program_start_date;
      }

        if (!isset($request->period))
        {
            return back()->with('danger','Dönem Aralığı Seçimi Yapılamadı');
        }
        if (!isset($request->applicant))
        {
            return back()->with('danger','Katılımcılar Seçilemedi Şube Seçip Filtrele Butonuna Basınız.Daha Sonra Programa Dahil Etmek İstediğiniz Personelleri Seçiniz');
        }

        $target_start_date = Carbon::now()->endOfMonth()->toDateString();
        $target_finish = Carbon::now()->addMonth($request->evalation_time)->toDateString();
        $sgk_company = getSgkCompany();
        $performce_program = PerformanceProgram::create([
            'company_id' => Auth::user()->company_id,
            'sgk_company_id'=>$sgk_company->id,
            'name' => $request->program_name,
            'program_employee_id' => !empty($request->program_employee) ? $request->program_employee : 0,
            'period' => $request->period,
            'weight' => $request->weight,
            'start_date' => $now_date,
            'finish_date' => $finish_date,
           // 'top_zam' =>  $request->max_zam,
           // 'min_zam' => $request->min_zam,
            'evalation_time'=>$request->evalation_time,
            'target_start_date' => $target_start_date,
            'target_finish_date' => $target_finish,
            'top_form_id' => $top_form->id,
            'base_form_id' => $base_form->id,
            'date' => Date::now()
        ]);

        foreach ($request->performance_type as $slug_en => $puan)
        {
            $type = PerformanceType::where('slug_en',$slug_en)->first();

            PerformanceProgramType::create([
                'company_id' => Auth::user()->company_id,
                'performance_program_id' => $performce_program->id,
                'performance_type_id' => $type->id,
                'puan' => $puan
            ]);
        }


        foreach ($request->applicant as $key => $applicant)
        {
            PerformanceApplicant::create([
                'performance_program_id'=>$performce_program->id,
                'employee_id' => $applicant
            ]);
        }

        if ($performce_program)
        {
            if (Auth::user()->hasRole('Performance'))
            {
                return redirect(route('questions.index'))->with('success','Kayıt İşlemi Başarılı');
            }
            else
            {
                return redirect(route('performance.store'))->with('success','Kayıt İşlemi Başarılı');
            }

        }
        else
        {
            return back()->with('danger','Kayıt İşlemi Başarısız');
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
        $performance = PerformanceProgram::find($id);
        if (!$performance)
        {
            return back()->with('danger','Program Bulunamadı');
        }
        $selectedProgramApplicant = PerformanceApplicant::where('performance_program_id',$performance->id)->get()->pluck('employee_id')->toArray();
        $employees = Employee::where('company_id',Auth::user()->company_id)->get();
        $employees2 = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
        $sgk_companies = SgkCompany::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $ust_ast_question_forms = CompanyQuestion::where('company_id',Auth::user()->company_id)->where('type',1)->get()->pluck('name','id');
        $ast_ust_question_forms = CompanyQuestion::where('company_id',Auth::user()->company_id)->where('type',2)->get()->pluck('name','id');
        $performance_types = PerformanceType::all();
        $selectedProgramType = PerformanceProgramType::where('performance_program_id',$id)->pluck('performance_type_id')->toArray();
        $selectedProgramTypes = PerformanceProgramType::where('performance_program_id',$id)->get();
        $departments = Department::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        $toplam_puan = PerformanceProgramType::where('performance_program_id',$id)->sum('puan');

        return view('performances.programs.edit', compact('performance','employees2','selectedProgramApplicant','employees','sgk_companies','ast_ust_question_forms','ust_ast_question_forms','performance_types','departments','selectedProgramType','selectedProgramTypes','toplam_puan','id'));
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

        $performce_program = PerformanceProgram::find($id);

        if (!$performce_program)
        {
            return back()->with('danger','Beklenmeyen bİr hatayla Karşılaşıldı');
        }

        if ($performce_program->status == 0)
        {
            return back()->with('danger','Tamamlanan Program Güncellenmez');
        }

        if ($request->applicant == null)
        {
            return back()->with('danger','Katılımcılar seçilmedi filtrele diyerek katılımcıları seçiniz ');
        }


        foreach ($request->performance_type as $key => $type)
        {
            $types[] = $type;
        }

        if (array_sum($types) != 100)
        {
            return back()->with('danger','Performans Türleri Değer Toplamaları 100 Olmalıdır');
        }



        $target_start_date = Carbon::now()->endOfMonth()->toDateString();
        $target_finish = Carbon::now()->endOfMonth()->addMonth($request->evalation_time)->toDateString();
        $sgk_company = getSgkCompany();
        $performce_program = PerformanceProgram::where('id',$id)->update([
            'company_id' => Auth::user()->company_id,
            'sgk_company_id' => $sgk_company->id,
            'name' => $request->program_name,
            'program_employee_id' => !empty($request->program_employee) ? $request->program_employee : 0,
            'period' => $request->period,
            'weight' => $request->weight,
            'top_zam' =>  $request->max_zam,
            'min_zam' => $request->min_zam,
          //   'top_puan' => $request->tavan,
          //  'base_puan' => $request->taban,
            'evalation_time'=>$request->evalation_time,
            'top_form_id' => $request->ust_form,
            'base_form_id' => $request->ast_form,
            'date' => Date::now()
        ]);

            PerformanceEvaluation::where('performance_program_id',$id)->delete([]);
        foreach ($request->competence as $evalaton)
        {
            $evaluations = PerformanceEvaluation::create([
                'performance_program_id'=>$id,
                'type_id'=>$evalaton
            ]);
        }
        PerformanceApplicant::where('performance_program_id',$id)->delete([]);

        foreach ($request->applicant as $key => $applicant)
        {

            PerformanceApplicant::create([
                'performance_program_id'=>$id,
                'employee_id' => $applicant
            ]);
        }
        PerformanceProgramType::where('performance_program_id',$id)->delete([]);

        foreach ($request->performance_type as $slug_en => $puan)
        {

            $type = PerformanceType::where('slug_en',$slug_en)->first();
            if (!$type)
            {
                PerformanceProgramType::create([
                    'company_id' => Auth::user()->company_id,
                    'performance_program_id' => $id,
                    'performance_type_id' => $slug_en,
                    'puan' => $puan
                ]);
            }
            else
            {
                PerformanceProgramType::create([
                    'company_id' => Auth::user()->company_id,
                    'performance_program_id' => $id,
                    'performance_type_id' => $type->id,
                    'puan' => $puan
                ]);
            }


        }


        if ($performce_program)
        {
            return redirect(route('performance.store'))->with('success','Kayıt İşlemi Başarılı');
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

    public function performance($id)
    {
        session(['performance_id'=>$id]);
        $performances = PerformanceType::all();
        return view('performances.programs.questions.invalations.index',compact('performances','id'));
    }

    public function reports_index()
    {
        if (!Auth::user()->hasRole('Performance'))
        {
            return back()->with('danger','Buraya Giriş Yetkiniz Yoktur');
        }

            $performances = PerformanceProgram::where('company_id',Auth::user()->company_id)->get();
        $backDay = [];
        if (count($performances) > 0)
        {
            foreach ($performances as  $performance)
            {
                $ilktarih = strtotime(date('Y/m/d'));
                $sontarih = strtotime($performance->target_finish_date);
                $gunfarki = ($sontarih - $ilktarih)/86400;
                $backDay[$performance->id] = $gunfarki;
                $applicant_count[$performance->id] = PerformanceApplicant::where('performance_program_id',$performance->id)->count();
                $applicants = PerformanceApplicant::where('performance_program_id',$performance->id)->get();
                $top_manager_count = 0;
                foreach ($applicants as $applicant)
                {
                    $employee = Employee::where('id',$applicant->employee_id)->first();

                    if (!empty($employee->top_manager_id))
                    {
                            $top_manager_count = $top_manager_count +1;
                    }
                    $subordinate_count[$applicant->employee_id] = EmployeeSubordinate::where('employee_id',$applicant->employee_id)->count();
                }

            }
               $toplam = $top_manager_count + array_sum($subordinate_count) + array_sum($applicant_count);
                    if ($toplam <=0)
                    {
                        $sonuc = 0;
                    }
                    else
                    {
                        $sonuc = (5 * 100)/$toplam;

                    }

        }

        else
        {
            $performances = [];
            $backDay = [];
        }


        return view('performances.programs.reports.index',compact('performances','backDay','sonuc','toplam'));
    }
    public function interest_performance()
    {
        $programs = PerformanceProgram::where('company_id',Auth::user()->company_id)->get()->pluck('name','id');
        if (!$programs)
        {
            return back()->with('danger','Program Bulunamadı');
        }
        return view('performances.programs.interests.performance_interest',compact('programs'));
    }
   public function interest_performance_store(Request $request)
    {

        $control = PerformanceProgram::find($request->performances);

//        if (!empty($control->top_zam) or !empty($control->min_zam))
//        {
//            return back()->with('warning','BU PROGRAM İÇİN ZAM YAPILDI!!');
//        }

        $update = PerformanceProgram::where('id',$request->performances)->update([
            'top_zam' => $request->max_zam,
            'min_zam' => $request->min_zam
        ]);


        if ($update)
        {

            $program_id = $request->performances;
            $program = PerformanceProgram::find($program_id);
            $program_types = PerformanceProgramType::where('performance_program_id',$program_id)->get();
            $program_applicants = PerformanceApplicant::where('performance_program_id',$program_id)->get()->pluck('employee_id')->toArray();
            $employees = Employee::whereIn('id',$program_applicants)->get();
            $toplam_puan_Degerlendirme =  PerformanceProgramType::where('performance_program_id',$program_id)->sum('puan');

            foreach ($program_types as $type)
            {
                foreach ($employees as $employee)
                {

                    $abc = $type->performance_type_puan2($employee->id,$type->performance_program_id,$type->performance_type_id);
                    $employee_toplam_puan[$employee->id][] = $abc;
                    $degerler[$employee->id][] = $abc;
                }

            }

            $zam_oran = array('min_zam' => $program->min_zam,'max_zam' => $program->top_zam);
            foreach ($employees as $employee)
            {
                $type_puan[$employee->id] = [];
                if (empty($employee->top_manager_id))
                {

                    $i =  PerformanceProgramType::where('performance_program_id',$program_id)
                        ->where('performance_type_id',1)
                        ->first();
                    $type_puan[$employee->id][] = $i->puan;
                }

                $subordinate = EmployeeSubordinate::where('employee_id',$employee->id)->get()->pluck('subordinate_id');
                $employee_subordinate = PerformanceApplicant::where('performance_program_id',$program_id)->whereIn('employee_id',$subordinate)->get()->toArray();
                if (count($employee_subordinate) == 0 )
                {

                    $i =  PerformanceProgramType::where('performance_program_id',$program_id)
                        ->where('performance_type_id',2)
                        ->first();

                     $type_puan[$employee->id][] = $i->puan;


                }

                $toplam_puan_Degerlendirme = (int)$toplam_puan_Degerlendirme;
                $deger = count($type_puan[$employee->id]) > 0 ? array_sum($type_puan[$employee->id]) : 0;
                $degerlendirme_sonuc = $toplam_puan_Degerlendirme - $deger;
                $toplam_puan = array_sum($degerler[$employee->id]);
                $sonuclarr[$employee->id] = ($toplam_puan * 100)/$degerlendirme_sonuc;

                if($employee->employeeSalary->salary_type == 1)
                {
                   // $brüt = $employee->employeeSalary->salary / 0.71491;
                    $brut = $employee->employeeSalary->salary;
                    $oran =  ($request->max_zam *  $sonuclarr[$employee->id])/100;
                    if ($oran < $request->min_zam)
                    {
                        $oran = $request->min_zam;
                    }
                    $brut2 = ($brut * $oran)/100;
                    $sonucc = $brut + $brut2;
                }
                else
                {
                    $oran =  ($request->max_zam *  $sonuclarr[$employee->id])/100;
                    if ($oran < $request->min_zam)
                    {
                        $oran = $request->min_zam;
                    }
                    $brut = ($employee->employeeSalary->salary * $oran)/100;
                    $sonucc = $brut + $employee->employeeSalary->salary;
                }
                $salary_control = EmployeeInterest::where('program_id',$type->performance_program_id)->where('employee_id',$employee->id)->first();
                if (!$salary_control)
                {
                    $salary = EmployeeInterest::create([
                        'employee_id'=>$employee->id,
                        'program_id'=>$type->performance_program_id,
                        'back_salary'=>$employee->employeeSalary->salary,
                        'new_salary'=>$sonucc,
                        'yuzde'=>$oran,
                        'date'=>\date('Y-m-d'),
                        'type'=>'Performans Program Zammı'
                    ]);
                }


//                EmployeeSalary::where('employee_id',$employee->id)->update([
//                    'salary' => $sonucc
//                ]);

                $name = '<tr style="height: 25px">
                        <td style="height: 25px;font-size: 15px;font-weight: bold">'. $employee->full_name .'</td>';

                $toplam_puan = $sonuclarr[$employee->id];
                $sonuclar = ' <td style="font-size: 15px;font-weight: bold">'.number_format($toplam_puan,2,",",".").'</td>';


                if ($toplam_puan <= 50)
                {
                    $paint = '<td style="background-color: red;font-size: 15px;font-weight: bold;color: black">Beklentinin Altında</td>';
                }
                elseif ($toplam_puan > 50 and $toplam_puan <= 69)
                {
                    $paint = '<td style="background-color: yellow;font-size: 15px;font-weight: bold;color: black">Beklenen seviyeye yakın</td>';
                }
                elseif ($toplam_puan > 69 and $toplam_puan <= 80)
                {
                    $paint = '<td style="background-color: blue;font-size: 15px;font-weight: bold;color: black">Beklenen seviyede</td>';
                }
                else
                {
                    $paint = '<td style="background-color: #90ff90;font-size: 15px;font-weight: bold;color: black">Beklenen seviyenin üstü</td>';
                }
                $zam = ' <td style="font-size: 15px;font-weight: bold"">'.number_format($oran,2,',','.').'</td>';

                if(isset($employee->employeeSalary->salary_type))
                {
                    if($employee->employeeSalary->salary_type == 1)
                    {
                       // $zam_sonuc = number_format($employee->employeeSalary->salary / 0.71491 , 2 , ',','.');
                        $zam_sonuc = number_format($employee->employeeSalary->salary , 2 , ',','.');
                    }
                    else
                    {
                        $zam_sonuc = number_format($employee->employeeSalary->salary,2,',','.');
                    }
                }
                else
                {
                    $zam_sonuc = '<td>data Girilmemiş</td>';
                }

                if(isset($employee->employeeSalary->salary_type))
                {
                    if($employee->employeeSalary->salary_type == 1)
                    {
                       // $brüt = $employee->employeeSalary->salary / 0.71491;
                        $brut = $employee->employeeSalary->salary;
                        $oran =  ($zam_oran['max_zam'] * $toplam_puan)/100;
                        if ($oran < $request->min_zam)
                        {
                            $oran = $request->min_zam;
                        }
                        $brut2 = ($brut * $oran)/100;
                        $zam_sonuc2 =  '<td>'.number_format($brut + $brut2 ,2, ',','.').' '.'₺</td>';
                    }
                    else
                    {
                        $oran =  ($zam_oran['max_zam'] * $toplam_puan)/100;

                        if ($oran < $request->min_zam)
                        {
                            $oran = $request->min_zam;
                        }
                        $brut = ($employee->employeeSalary->salary * $oran)/100;
                        $zam_sonuc2 = '<td>'.number_format($brut + $employee->employeeSalary->salary,2,',','.').' '.'₺</td>';
                    }
                }
                else
                {
                    $zam_sonuc2  = '<td>data Girilmemiş</td>';
                }
                $genel[] = $name.$sonuclar.$paint.$zam.'<td>'.$zam_sonuc.'</td>'.$zam_sonuc2.'</tr>';



            }
            $thead [] ='<tr>
                     <th colspan="6" style="text-align: center;height: 80px;font-weight: bold;font-size: 17px;">GENEL PERFORMANS DEĞERLENDİRME RAPORU</th></tr>';

            $thead[] = '<tr style="width: 25px;;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold;font-size: 15px">
<th style="width: 25px;;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold;font-size: 15px">isim Soyisim</th> <th style="width: 25px;;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold;font-size: 15px">Toplam Performans Puanı</th>
                                    <th style="background-color: #ffc107;font-size: 15px;width: 15px">Puan Tanımı</th>
                                    <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold;font-size: 15px">Zam Oranı</th>
                                    <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold;font-size: 15px">Mevcut  Ücret</th>
                                    <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold;font-size: 15px">Yeni Ücret</th>
                                   </tr>';

            $button =   '<a target="_blank"  href="https://ik.ikmetrik.com/performances_user_reports/document/'.createHashId($program->id).'" class="navi-link btn btn-success">
																			<span class="navi-icon">
																				<i class="flaticon2-user-1"></i>
																			</span>
                                                    <span class="navi-text">Toplu Rapor</span>
                                                </a>';
            $button = $button.'<a data-url="https://ik.ikmetrik.com/performance-interest-save/'.createHashId($program->id).'/'.$request->min_zam.'/'.$request->max_zam.'" class="navi-link btn btn-success ml-3 performanceInterestSave">
																			<span class="navi-icon performanceInterestSave">
																				<i class="flaticon2-user-1 performanceInterestSave"></i>
																			</span>
                                                    <span class="navi-text performanceInterestSave">Performans Zam Kaydet</span>
                                                </a>';


            return response()->json([
                'type'=>'success',
                'tables' => $genel,
                'table_thead' => $thead,
                'html' => $button


            ]);


          //  $toplam_puan_Degerlendirme =  PerformanceProgramType::where('performance_program_id',$program_id)->sum('puan');
           // return view('performances.programs.interests.performance_interest',compact('programs','program','puans', 'zam_oran','program_types','employees','employee_toplam_puan','company','company_toplam_puan','sonuclar'));
           // return redirect(route('questions.index'))->with('success','Kayıt İşlemi Başarılı');
        }
        else
        {
            return redirect(route('questions.index'))->with('danger','Herhangi Bir Değişiklik Yapılmadı');
        }
    }
    public function seyyanen_interest()
    {
        $employees = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
        $employees2 = Employee::where('company_id',Auth::user()->company_id)->get();
        if (!$employees2)
        {
            return back()->with('danger','Çalışan Bulunamadı');
        }
        foreach ($employees2 as $employee) {

            $salary[$employee->id] = !empty($employee->employeeSalary->salary) ?  $employee->employeeSalary->salary : 0;
        }
        if (!isset($salary))
        {
            $salary = [];
        }
        return view('performances.programs.interests.seyyanen_interest',compact('employees','salary'));
    }
    public function employee_salary_interest($id)
    {
        $salary = EmployeeSalary::where('employee_id',$id)->first();
        $salary_type = config('variables.employees.salary_type')[$salary->salary_type];


        return response()->json([
            'salary' => $salary->salary,
            'salary_type' => $salary_type
        ]);
    }
    public function interest_seyyanen_store(Request $request)
    {
        $sonuc = ($request->salary * $request->zam_oran)/100;
        $sonuc = $request->salary + $sonuc;

        $control = Employee::where('id',$request->employee)->first();
        if (!$control)
        {
            return back()->with('danger','Personel Bulunamadı');
        }
        $control2 = EmployeeSalary::where('employee_id',$request->employee)->first();
        if (!$control2)
        {
            return back()->with('danger','Personel Maaş Bilgisi Bulunamadı Personel Sicil Kartları Bölümünden Çalışanın Maasş Bilgilerini Kontrol Ediniz');
        }
        $datas = array('back_salary'=>$control2->salary,'zam_oran'=>$request->zam_oran,'new_salary'=>$sonuc,'name'=>$control->full_name);
        $salary = EmployeeInterest::create([
            'employee_id'=>$request->employee,
            'back_salary'=>$control->employeeSalary->salary,
            'new_salary'=>$sonuc,
            'yuzde' => $request->zam_oran,
            'date'=>\date('Y-m-d'),
            'type'=>'Seyyanen Ücret Zammı'
        ]);
          $update =   EmployeeSalary::where('employee_id',$request->employee)->update([
            'salary' => $sonuc
        ]);
        if ($update)
        {
            $employees = Employee::where('company_id',Auth::user()->company_id)->get()->pluck('full_name','id');
            $employees2 = Employee::where('company_id',Auth::user()->company_id)->get();
            if (!$employees2)
            {
                return back()->with('danger','Çalışan Bulunamadı');
            }
            foreach ($employees2 as $employee) {

                $salary[$employee->id] = !empty($employee->employeeSalary->salary) ?  $employee->employeeSalary->salary : 0;
            }
            if (!isset($salary))
            {
                $salary = [];
            }
            return view('performances.programs.interests.seyyanen_interest',compact('employees','salary','datas'));
        }
        else
        {
            return redirect(route('questions.index'))->with('danger','Herhangi Bir Değişiklik Yapılmadı');
        }
    }
    public function metric_index()
    {
        $ids = [2,3,8,7,9,11,13,14,15,21,22,26,28,31,30,25];

        $metric_reports = MetricReport::whereIn('id',$ids)->get();
        return view('performances.metrics.index',compact('metric_reports'));
    }
    public function interest()
    {
        $sgk_company_id = session()->get('selectedCompany')['id'];
        $sgk_company = SgkCompany::find($sgk_company_id);

        if (!$sgk_company)
        {
            return back('danger','Firma Seçilemedi');
        }
        $employees = Employee::where('sgk_company_id',$sgk_company_id)->get()->pluck('full_name','id');
        return view('performances.programs.interests.calendar',compact('employees'));
    }

    public function delete($id)
    {
        $id = HashingSlug::decodeHash($id);

        $performance = PerformanceProgram::find($id);
        if (!$performance)
        {
            return back()->with('danger','Performans Program Bulunamadı');
        }
        $performance->delete();
        return redirect(route('performance.index'))->with('success','Silme İşlemi Başarılı');
    }
}
