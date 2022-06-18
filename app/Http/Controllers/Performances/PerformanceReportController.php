<?php

namespace App\Http\Controllers\Performances;

use App\Exports\PerformancePolivalansExport;
use App\Exports\PerformanceProgramExport;
use App\Exports\PolivalansAllExort;
use App\Helpers\HashingSlug;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyJobQuestionService;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeSubordinate;
use App\Models\PerformanceApplicant;
use App\Models\PerformanceForm;
use App\Models\PerformanceProgram;
use App\Models\PerformanceProgramEvalation;
use App\Models\PerformanceProgramType;
use App\Models\PerformanceType;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Converter;
use Excel;

class PerformanceReportController extends Controller
{
    public function applicantReport($id)
    {
        $id = HashingSlug::decodeHash($id);
        $applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
        $performance_types = PerformanceProgramType::where('performance_program_id',$id)->orderBy('performance_type_id','ASC')->get();
        $employees = Employee::whereIn('id',$applicants)->get();
        return view('performances.programs.reports.applicants_index',compact('employees','performance_types','id'));
    }
    public function applicantReportDocument($employee_id,$program_id)
    {
        $program_types = PerformanceProgramType::where('performance_program_id',$program_id)->get();
        if (count($program_types) <= 0)
        {
            return back()->with('danger','beklenmeyen bir Hatayla Karşılaşıldı');
        }

        $employee = Employee::find($employee_id);

        $top_manager = Employee::find($employee->top_manager_id);

        $program = PerformanceProgram::find($program_id);
        foreach ($program_types as $type)
        {
            $puans[$type->performance_type_id] = $type->performance_type_puan2($employee->id,$type->performance_program_id,$type->performance_type_id);
        }
        $toplam_puan_Degerlendirme =  PerformanceProgramType::where('performance_program_id',$program_id)->sum('puan');

        foreach ($puans as $key => $puan)
        {
           if ($puan == 0)
           {
               $i =  PerformanceProgramType::where('performance_program_id',$program_id)
                   ->where('performance_type_id',$key)
                   ->first();
               if (isset($i->puan))
               {
                   $type_puan[] = $i->puan;
               }
               else
               {
                   $type_puan[] = 0;
               }

           }
           else
           {
               $say_puan[] = $puan;
           }

        }
        $degerlendirme_sonuc = $toplam_puan_Degerlendirme - array_sum($type_puan);
        $sonuc = (array_sum($say_puan) * $degerlendirme_sonuc)/100;
        $sonuc = ($sonuc * 100)/$degerlendirme_sonuc;

            if ($sonuc <= 50)
            {
                $puan_karsilik = 'Beklentinin Altında';
            }
            elseif ($sonuc > 50 and $sonuc <= 69)
            {
                $puan_karsilik = 'Beklenen seviyeye yakın';
            }
            elseif ($sonuc > 69 and $sonuc <= 80)
            {
                $puan_karsilik = 'Beklenen seviyede';
            }
            else
            {
                $puan_karsilik = 'Beklenen seviyenin üstü';
            }

           $evalation = PerformanceProgramEvalation::
                where('type_id',10)
               ->where('evalation_id',$employee->id)
               ->first();
            $educations = [];
            $values = [];

            if ($evalation)
            {
                $forms = PerformanceForm::where('performance_program_evalation_id',$evalation->id)->get()->toArray();
                $qustions_count = count($forms);

                $tob_puan = 100 / $qustions_count;
                $min_puan = $tob_puan / 4;
                $orta_puan = $min_puan * 2;
               foreach ($forms as $form)
               {
                   if ($form['puan'] == $min_puan )
                   {
                      $educations[] = array('0' => $form['puan'],'1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(),'2'=>'En Düşük Puanı Almıştır');
                   }

                   if($form['puan'] == $orta_puan)
                   {
                       $educations[] = array('0' => $form['puan'],'1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(),'2'=>'Orta Puanı Almıştır');

                   }
               }
               $sira = 0;
               foreach ($educations as $key => $education)
               {
                  $sira++;
                   $values[] = array('row'=> $sira, 'question'=> $education[1]['question'], 'puan' => $education[0].'/'.$tob_puan ,'durum' => $education[2]);
               }



            }

             $educations_question =  education_analiz($employee,$program_id);

        $sira = 0;
        foreach ($educations_question as $question)
        {

            $sira++;
            $educationss[] = array('row'=>$sira,'question'=>$question['type'],'puan' => number_format($question['ortalama'],2,',','.') ,'durum' => $question['sonuc']);
        }

        foreach ($values as $value)
        {
            $sira++;
            $educationss[] = array('row'=>$sira,'question'=>$value['question'],'puan' => $value['puan'],'durum' => $value['durum']);
        }



        $company = Company::find(\Auth::user()->company_id);


        Settings::setOutputEscapingEnabled(true);
            $department_name = $employee->department->name;
            $ust_department_name = !empty($top_manager->department->name) ? $top_manager->department->name: null ;
            $random = rand(0, mt_getrandmax());
            $fileName = \Auth::user()->company_id . '-' . 'company_documents' . DIRECTORY_SEPARATOR . 'Word' . DIRECTORY_SEPARATOR .$random . ".docx";
            $template = new TemplateProcessor('documents/1.docx');
            $template->setImageValue('company_logo', array(public_path($company->logo), 'height' => 150, 'width' => 150));
            $template->setValue('name', $employee->full_name);
            $template->setValue('puan_karsiligi', $puan_karsilik);
            $template->setValue('ust_name', !empty($top_manager->full_name) ? $top_manager->full_name : 'Üst Yönetici Yoktur' );
            $template->setValue('department', $department_name);
            $template->setValue('ust_department', !empty($top_manager->department->name) ? $top_manager->department->name : null );
            $template->setValue('program_start_date', $program->start_date->format('d/m/Y'));
            $template->setValue('program_finish_date', $program->finish_date->format('d/m/Y'));
            $template->setValue('toplam_puan', number_format($sonuc,2,',','.'));
            $template->setValue('oz_puan', isset($puans[3]) ? number_format($puans[3],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('ast_puan', isset($puans[2]) ? number_format($puans[2],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('ust_puan', isset($puans[1]) ? number_format($puans[1],2,',','.') : 'Puanlama Yapılmamıştırr');
            $template->setValue('disiplin', isset($puans[4]) ? number_format($puans[4],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('egitim', isset($puans[5]) ? number_format($puans[5],2,',','.'):'Puanlama Yapılmamıştırr');
            $template->setValue('devamsizlik', isset($puans[6]) ? number_format($puans[6],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('sosyal', isset($puans[7]) ? number_format($puans[7],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('kidem', isset($puans[8]) ? number_format($puans[8],2,',','.') : 'Puanlama Yapılmamıştır' );
            $template->setValue('dil', isset($puans[9]) ? number_format($puans[9],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('meslek', isset($puans[10]) ?  number_format($puans[10],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('management_puan', isset($puans[11]) ?  number_format($puans[11],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('es_deger', isset($puans[12]) ?  number_format($puans[12],2,',','.') : 'Puanlama Yapılmamıştır');
            $template->setValue('date', date('d/m/Y'));
            $template->cloneRowAndSetValues('row',$educationss);
//            $template->cloneRowAndSetValues('sira',$educations_question);
            $aa = PerformanceProgramType::where('performance_program_id',$program_id)->get()->pluck('performance_type_id')->toArray();

            $categories = PerformanceType::whereIn('id',$aa)->pluck('name')->toArray();
            foreach ($aa as $id)
            {
                if ($puans[$id] > 0)
                {
                    $series1[] = $puans[$id];
                }


            }
            if (!isset($series1))
            {
                $series1 = array(isset($puans[1]) ? $puans[1] : null, isset($puans[2]) ? $puans[2] : null, isset($puans[3]) ? $puans[3] : null, isset($puans[4]) ? $puans[4] : null, isset($puans[5]) ? $puans[5] : null,isset($puans[6]) ? $puans[6] : null,isset($puans[7]) ? $puans[7] : null,isset($puans[8]) ? $puans[8] : null,isset($puans[9]) ? $puans[9] : null,isset($puans[10]) ? $puans[10] : null,isset($puans[11]) ? $puans[11] : null,isset($puans[12]) ? $puans[12] : null);

            }
        foreach ($categories as $category)
        {
            $degerler = explode(" ",$category);
            if (isset($degerler[1]))
            {
                $a =substr($degerler[1] ,0,1 );
                $ccc[] = $degerler[0].'.'.$a;
            }
            else
            {
                $ccc[] = $degerler[0];
            }




        }
        $categories =[];
        $categories = $ccc;

        $chart = new Chart('line', $categories, $series1);
            $colors = array(
            'FF6633', 'FFB399', 'FF33FF', 'FFFF99', '00B3E6',
            'E6B333', '3366E6', '999966', '99FF99', 'B34D4D',
            '80B300', '809900'
             );

            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addTitle('Performans');
           $chart->getStyle()->setWidth(Converter::inchToEmu(7))->setHeight(Converter::inchToEmu(3.5));
           $chart->getStyle()->setColors($colors);
           $chart->getStyle()->setShowAxisLabels(true);
           $chart->getStyle()->setShowGridX(true);
           $chart->getStyle()->setShowGridY(true);
            $template->setChart('myChart', $chart);
            $template->saveAs(storage_path('app/performance_reports/company_documents/word/'.$random.'.docx'));
            return response()->download(storage_path('app/performance_reports/company_documents/word/'.$random.'.docx'));

    }
    public function performance_program_report($program_id)
    {
        $program_id = HashingSlug::decodeHash($program_id);
        $company = Company::find(\Auth::user()->company_id);
        $program = PerformanceProgram::find($program_id);
        $program_types = PerformanceProgramType::where('performance_program_id',$program_id)->get();
        $program_applicants = PerformanceApplicant::where('performance_program_id',$program_id)->get()->pluck('employee_id')->toArray();
        $employees = Employee::whereIn('id',$program_applicants)->get();
        $toplam_puan_Degerlendirme =  PerformanceProgramType::where('performance_program_id',$program_id)->sum('puan');
        foreach ($program_types as $type)
        {

            foreach ($employees as $employee)
            {

                $puans[$employee->id][$type->performance_type_id][] = $type->performance_type_puan2($employee->id,$type->performance_program_id,$type->performance_type_id);
                $employee_toplam_puan[$employee->id][] = $type->performance_type_puan2($employee->id,$type->performance_program_id,$type->performance_type_id);
                $company_toplam_puan[] = $type->performance_type_puan2($employee->id,$type->performance_program_id,$type->performance_type_id);
            }

        }

        foreach ($employees as $employee)
        {
            $type_puan[$employee->id] = [];

            if (empty($employee->top_manager_id))
            {
                $i =  PerformanceProgramType::where('performance_program_id',$program_id)
                    ->where('performance_type_id',1)
                    ->first();
                if (isset($i->puan))
                {
                    $type_puan[$employee->id][] = $i->puan;
                }
                else
                {
                    $type_puan[$employee->id][] = 0;
                }

            }

            $subordinate = EmployeeSubordinate::where('employee_id',$employee->id)->get()->pluck('subordinate_id');
            $employee_subordinate = PerformanceApplicant::where('performance_program_id',$program_id)->whereIn('employee_id',$subordinate)->get()->toArray();
            if (count($employee_subordinate) == 0 )
            {
                $i =  PerformanceProgramType::where('performance_program_id',$program_id)
                    ->where('performance_type_id',2)
                    ->first();

                if (isset($i->puan))
                {
                    $type_puan[$employee->id][] = $i->puan;
                }
                else
                {
                    $type_puan[$employee->id][] = 0;
                }
            }

            $toplam_puan_Degerlendirme = (int)$toplam_puan_Degerlendirme;
            $deger = count($type_puan[$employee->id]) > 0 ? array_sum($type_puan[$employee->id]) : 0;
            $degerlendirme_sonuc = $toplam_puan_Degerlendirme - $deger;
            $toplam_puan = array_sum($employee_toplam_puan[$employee->id]);
            $sonuclar[$employee->id] = ($toplam_puan * 100)/$degerlendirme_sonuc;
        }


        $zam_oran = array('min_zam' => $program->min_zam,'max_zam' => $program->top_zam);
        $toplam_puan_Degerlendirme =  PerformanceProgramType::where('performance_program_id',$program_id)->sum('puan');
        return Excel::download(new PerformanceProgramExport($puans, $zam_oran,$program_types,$employees,$employee_toplam_puan,$company,$company_toplam_puan,$sonuclar,$type_puan), 'Performans-Sonuc-Raporu.xlsx');





    }
    public function education_report($id)
    {
        $id = HashingSlug::decodeHash($id);
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
           $dates[$applicant] =  education_analiz($employee,$id);
       }
       foreach ($employees as $employee)
       {
           if (isset($dates[$employee->id]))
           {
               foreach($dates[$employee->id] as $education)
               {

                   $educations[$employee->id][] = '<li>'.$education['type'].'</li>';
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

           $datas[] = '<tr class="type_applicant"> <td class="type_applicant">'. $employee->full_name.'</td><td class="type_applicant">'.$performance_program->name.'</td><td class="type_applicant"><ul>'.$a.'</ul></td></tr>';
       }

            return view('performances.programs.reports.education_index',compact('employees','performance_program','datas'));

    }
    public function polivalans_report($id,$department_id)
    {

        $id = HashingSlug::decodeHash($id);
        $selectedDepartment = $department_id;
        $performance_program = PerformanceProgram::find($id);

        $applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
        if ($department_id > 0)
        {

            $employees = Employee::whereIn('id',$applicants)->where('department_id',$department_id)->get();
        }
        else
        {
            $employees = Employee::whereIn('id',$applicants)->get();
        }
        $departments = Department::where('company_id',\Auth::user()->company_id)->get()->pluck('name','id');
        $values = [];


        foreach ($applicants as $employee_id)
        {

            $employee = Employee::find($employee_id);

            $evalation = PerformanceProgramEvalation::
            where('type_id',10)
                ->where('evalation_id',$employee->id)
                ->first();



            if ($evalation)
            {
                $forms = PerformanceForm::where('performance_program_evalation_id',$evalation->id)->get()->toArray();

                $qustions_count = count($forms);

                $tob_puan = 100 / $qustions_count;
                $min_puan = $tob_puan / 4;
                $orta_puan = $min_puan * 2;
                $educations = [];

                foreach ($forms as $form)
                {
                    $puans[$employee_id][] = $form['puan'];

                    if ($form['puan'] == $min_puan )
                    {
                        $educations[$employee_id][] = array('0' => $form['puan'],'1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(),'2'=>'En Düşük Puanı Almıştır');
                    }

                    if($form['puan'] == $orta_puan)
                    {
                        $educations[$employee_id][] = array('0' => $form['puan'],'1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(),'2'=>'Orta Puanı Almıştır');

                    }
                }

                if (!empty($educations[$employee_id]))
                {

                    foreach ($educations[$employee_id] as $key => $education)
                    {

                        $values[$employee->id][] = array( 'question'=> $education[1]['question'], 'puan' => $education[0].'/'.$tob_puan ,'durum' => $education[2]);

                    }
                }

            }
        }


        return view('performances.programs.reports.polivalans_reports',compact('values','employees','performance_program','departments','selectedDepartment','puans'));
    }
    public function polivalans_report_excel(Request $request)
    {
        $employee_id = HashingSlug::decodeHash($request->employee_id);

        $employee = Employee::find($employee_id);
        $evalation = PerformanceProgramEvalation::
        where('type_id',10)
            ->where('evalation_id',$employee->id)
            ->first();
        $educations = [];
        $values = [];

        if ($evalation)
        {
            $forms = PerformanceForm::where('performance_program_evalation_id',$evalation->id)->get()->toArray();

            $qustions_count = count($forms);

            $tob_puan = 100 / $qustions_count;
            $min_puan = $tob_puan / 4;
            $orta_puan = $min_puan * 2;
            foreach ($forms as $form)
            {
                if ($form['puan'] == $min_puan )
                {
                    $educations[$employee_id][] = array('0' => $form['puan'],'1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(),'2'=>'En Düşük Puanı Almıştır');
                }

                if($form['puan'] == $orta_puan)
                {
                    $educations[$employee_id][] = array('0' => $form['puan'],'1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(),'2'=>'Orta Puanı Almıştır');

                }
            }

            foreach ($educations[$employee_id] as $key => $education)
            {

                $values[] = array( 'question'=> $education[1]['question'], 'puan' => $education[0].'/'.$tob_puan ,'durum' => $education[2]);
            }
        }
             $company = Company::find($employee->company_id);
             return Excel::download(new PerformancePolivalansExport($values,$company,$employee), 'Performans_Polivalans_Raporu' . ' - ' . date("Y-m-d") . '.xlsx');
    }
    public function polivalans_all_report($department_id,$id)
    {
        $applicants = PerformanceApplicant::where('performance_program_id', $id)->get()->pluck('employee_id')->toArray();
        if (count($applicants) <= 0) {
            return back()->with('Program Katılımcısı Bulunamadı');
        }
        $employees = Employee::where('department_id', $department_id)->whereIn('id', $applicants)->get();
        if (count($employees) <= 0) {
            return back()->with('Çalışan Bulunamadı');
        }

        foreach ($employees as $employee) {


            $employee_id = $employee->id;

            $employee = Employee::find($employee_id);
            $evalation = PerformanceProgramEvalation::
            where('type_id', 10)
                ->where('evalation_id', $employee->id)
                ->first();
            $educations = [];
            $values = [];

            if ($evalation) {
                $forms = PerformanceForm::where('performance_program_evalation_id', $evalation->id)->get()->toArray();
                $qustions_count = count($forms);

                $tob_puan = 100 / $qustions_count;
                $min_puan = $tob_puan / 4;
                $orta_puan = $min_puan * 2;
                foreach ($forms as $form) {
                    if ($form['puan'] == $min_puan) {
                        $educations[$employee_id][] = array('0' => $form['puan'], '1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(), '2' => 'En Düşük Puanı Almıştır');
                    }

                    if ($form['puan'] == $orta_puan) {
                        $educations[$employee_id][] = array('0' => $form['puan'], '1' => CompanyJobQuestionService::select('question')->find($form['question_id'])->toArray(), '2' => 'Orta Puanı Almıştır');

                    }
                }

                foreach ($educations[$employee_id] as $key => $education) {

                    $values[$employee->full_name][] = array('question' => $education[1]['question'], 'puan' => $education[0] . '/' . $tob_puan, 'durum' => $education[2]);
                }
            }

        }


        $company = Company::find($employee->company_id);

        return Excel::download(new PolivalansAllExort($values, $company, $employee), 'Performans_Polivalans_Raporu' . ' - ' . date("Y-m-d") . '.xlsx');

    }
    public function employee_department_report()
    {
        $dayFark = [];
        $id = \Auth::user()->employee_id;
        $performance_program_ids = PerformanceApplicant::where('employee_id',$id)->get()->pluck('performance_program_id')->toArray();
        $performances = PerformanceProgram::whereIn('id',$performance_program_ids)->get();
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
        return view('performances.programs.reports.employee_department_reports.index',compact('performances','dayFark'));
    }
    public function department_employee_report_show($id)
    {
        $employee_id = Auth::user()->employee_id;
        $employee = Employee::find($employee_id);

        $id = HashingSlug::decodeHash($id);
        $performance = PerformanceProgram::find($id);
        if (!$performance)
        {
            return view('danger','Program Bulunmadı');
        }
        $program_types = PerformanceProgramType::where('performance_program_id',$id)->get();
        $program_applicants = PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id')->toArray();
        $program_applicants_count = PerformanceApplicant::where('performance_program_id',$id)->count();
        if ($program_applicants_count <=0)
        {
            return back()->with('danger','Katılımcı Bulunmadı');
        }
        $employees = Employee::whereIn('id',$program_applicants)->where('department_id',$employee->department_id)->get();

        $employees_count = Employee::whereIn('id',$program_applicants)->where('department_id',$employee->department_id)->count();
        $employee_toplam_puan = [];
        foreach ($program_types as $type)
        {
            foreach ($employees as $employee)
            {
                $employee_toplam_puan[$employee->id][] = (float)$type->performance_type_puan($employee->id,$type->performance_program_id,$type->performance_type_id);
            }

        }
        $sonuc = '';
        if (count($employee_toplam_puan) > 0)
        {
            foreach ($employee_toplam_puan as $id => $puan)
            {
                if ($id == $employee_id) {

                    if (array_sum($puan) <= 50) {
                        $sonuc = 'Beklentinin Altında';
                    } elseif (array_sum($puan) > 50 and $sonuc <= 69) {
                        $sonuc = 'Beklenen seviyeye yakın';
                    } elseif (array_sum($puan) > 69 and $sonuc <= 80) {
                        $sonuc = 'Beklenen seviyede';
                    } else {
                        $sonuc = 'Beklenen seviyenin üstü';
                    }
                }


            }
        }

        $toplam_puans = 0;
        foreach ($employee_toplam_puan as $degerler)
        {
            $toplam_puans += array_sum($degerler);
        }
        $ortalama = $toplam_puans / $employees_count;
        return view('performances.programs.reports.employee_department_reports.show',compact('employee_toplam_puan','employees','ortalama','employee_id','sonuc'));

    }
     public function evalation_report_index($id)
    {
        $id = HashingSlug::decodeHash($id);
        $evalations = PerformanceProgramEvalation::where('performance_program_id',$id)->get();
        return view('performances.programs.reports.evalations_reports.index',compact('evalations'));
    }
    public function evalation_delete($id)
    {
        $id = HashingSlug::decodeHash($id);
        $evalation = PerformanceProgramEvalation::find($id);
        if (!$evalation)
        {
            return back()->with('danger','Silme İşlemi başarısız');
        }
        $sonuc = $evalation->delete();
        if ($sonuc)
        {
            return back()->with('success','Silme İşlemi Başarılı');
        }
        else
        {
            return back()->with('danger','Silme İşlemi Başarısız');
        }
    }
}
