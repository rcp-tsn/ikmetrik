<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PerformanceProgramType extends Model
{
    protected $guarded;

    public function performance_program()
    {
        return $this->hasOne('App\Models\PerformanceProgram','performance_program_id','id');
    }
    public function performance_type()
    {
        $type = PerformanceType::where('id',$this->performance_type_id)->first();
        return $type->name;
    }
    public function performance_type4()
    {
        $type = PerformanceType::where('id',$this->performance_type_id)->first();
        return $type->slug_en;
    }
    public function performance_type2()
    {
        $type = PerformanceType::where('id',$this->performance_type_id)->first();
        $data = array($type->name,$type->performance_type_id);
        return $data;
    }
    public function performance_type3()
    {
        $type = PerformanceType::where('id',$this->performance_type_id)->first();
        $data = $type->performance_type_id;
        return $data;
    }
    public function icon()
    {
        $type = PerformanceType::where('id',$this->performance_type_id)->first();
        return $type->icon;
    }
    public function performance_type_puan($employee_id,$performance_program_id,$type_id)
    {
        $performance_program_type = PerformanceProgramType::where('performance_program_id',$performance_program_id)
            ->where('performance_type_id',$type_id)->first();
        $performance_program = PerformanceProgram::find($performance_program_id);

        if (!empty($performance_program_type))
        {
            switch ($type_id) {
                case 1:
                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)->where('performance_program_id', $performance_program_id)->where('type_id',2)->first();
                    if ($evalation) {

                        $sumPuan = PerformanceForm::where('performance_program_evalation_id', $evalation->id)->sum('puan');
                        $sonuc = ($sumPuan * $performance_program_type->puan) / 100;
                        return number_format($sonuc, '2', ',', '.');
                    }
                    else
                    {
                        return 'Değerlendirme Yapılmadı';
                    }

                    break;
                case 2:


                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)->where('performance_program_id', $performance_program_id)->where('type_id',1)->get()->pluck('id')->toArray();
                    if ($evalation)
                    {
                        $degerlendirme_sayisi = count($evalation);

                        $sumPuan = PerformanceForm::whereIn('performance_program_evalation_id', $evalation)->sum('puan');
                        $sonuc = ($sumPuan / $degerlendirme_sayisi);
                        $sonuc  = ($performance_program_type->puan * $sonuc)/100;
                        return number_format($sonuc,'2',',','.');
                    }
                    else
                    {
                        return 'Değerlendirme Yapılmadı';
                    }
                    break;
                case 3:

                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)
                        ->where('employee_id',$employee_id)
                        ->where('performance_program_id', $performance_program_id)
                        ->where('type_id',3)
                        ->first();

                    if ($evalation)
                    {

                        $sumPuan = PerformanceForm::whereIn('performance_program_evalation_id', [$evalation->id])->sum('puan');

                        $sonuc = ($sumPuan * $performance_program_type->puan)/100;
                        return number_format($sonuc,'2',',','.');
                    }
                    else
                    {
                        return 'Değerlendirme Yapılmamış';
                    }
                    break;
                case 4:
                    $disciplines = PerformanceEmployeeDiscipline::where('discipline_date','>=',$performance_program->start_date)->where('discipline_date','<=',$performance_program->finish_date)
                        ->where('employee_id',$employee_id)->count();

                    $info = array('0' =>'100','1'=>'50','2'=>'10');
                    switch ($disciplines)
                    {
                        case 0 :
                            $sonuc = (100 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                            break;
                        case 1:
                            $sonuc = (50 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                            break;
                        case 2:
                            $sonuc = (10 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                            break;

                    }
                    if ($disciplines > 2 )
                    {
                        $sonuc = (10 * $performance_program_type->puan)/100;
                        return number_format($sonuc,2,',','.');
                        break;
                    }
                    break;
                case 5;
                    $employeeInfo = EmployeePersonalInfo::where('employee_id',$employee_id)->first();

                    if (!empty($employeeInfo->completed_education) and !empty($performance_program_type->puan))
                    {
                        if ($employeeInfo->completed_education == 0 or $employeeInfo->completed_education == 1 )
                        {
                            $sonuc = (10 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                        }
                        elseif($employeeInfo->completed_education == 2)
                        {
                            $sonuc = (30 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                        }
                        elseif($employeeInfo->completed_education == 3)
                        {
                            $sonuc = (50 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                        }
                        elseif($employeeInfo->completed_education == 4)
                        {
                            $sonuc = (70 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                        }
                        elseif($employeeInfo->completed_education == 5)
                        {
                            $sonuc = (90 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                        }
                        elseif($employeeInfo->completed_education == 6)
                        {
                            $sonuc = (100 * $performance_program_type->puan)/100;
                            return number_format($sonuc,2,',','.');
                        }
                    }
                    else
                    {
                        return 0;
                    }

                    break;
                case 6:

                    $days = Discontinuty::where('employee_id',$employee_id)->where('time_type',0)->get()->sum('time');
                    $saat = Discontinuty::where('employee_id',$employee_id)->where('time_type',1)->get()->sum('time');
                    $dakika = Discontinuty::where('employee_id',$employee_id)->where('time_type',2)->get()->sum('time');
                    $toplam_saat_gun = $saat / 24 ;
                    $toplam_dakika_gun = $dakika / 1440;
                    $sonuc = $toplam_saat_gun + $toplam_dakika_gun + $days;

                    if ($sonuc < 5 )
                    {
                        $deger = (100 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    elseif ($sonuc > 6 and $sonuc <= 10)
                    {
                        $deger = (70 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    else
                    {
                        $deger = (10 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }

                    break;
                case 7:
                    $employee = EmployeePersonalInfo::where('employee_id',$employee_id)->first();
                    if (!$employee)
                    {
                        return 'değer Yok';
                    }
                    $puanlar = [];

                    if ($employee->home == 1)
                    {
                        $puanlar[] = 33;
                    }
                    if($employee->university == 1)
                    {
                        $puanlar[] = 34;
                    }
                    if($employee->disability_level == 1 or $employee->disability_level == 2  )
                    {
                        $puanlar[] = 33;
                    }

                    $sonuc_puan = array_sum($puanlar);
                    if (!empty($performance_program_type->puan))
                    {
                        $deger = ($sonuc_puan * $performance_program_type->puan)/100;
                    }
                    else
                    {
                        $deger = 0;
                    }

                    return number_format($deger,2,',','.');
                    break;
                case 8:
                    $employee = Employee::find($employee_id);
                    $now_date = Carbon::now();
                    $date = Carbon::parse($employee->job_start_date);
                    $date = $date->diffInYears($now_date, false);
                    if ($date <= 1)
                    {
                        $deger = (20 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    if ($date >= 1 and $date <= 3)
                    {
                        $deger = (40 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    if ($date >= 4 and $date <= 5)
                    {
                        $deger = (60 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    if ($date >= 5 and $date <= 5)
                    {
                        $deger = (80 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    if ($date >= 6)
                    {
                        $deger = (100 * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    break;
                case 9:
                    $employees_languages = EmployeeLanguage::where('employee_id',$employee_id)->count();
                    if (!empty($performance_program_type->puan))
                    {
                        if ($employees_languages <=1)
                        {
                            $deger = (30 * $performance_program_type->puan)/100;
                            return number_format($deger,2,',','.');
                        }
                        if ($employees_languages > 1 and $employees_languages <= 2 )
                        {
                            $deger = (60 * $performance_program_type->puan)/100;
                            return number_format($deger,2,',','.');
                        }
                        if ($employees_languages > 2)
                        {
                            $deger = (100 * $performance_program_type->puan)/100;
                            return number_format($deger,2,',','.');
                        }
                    }
                    else
                    {
                        return 0;
                    }

                    break;
                case 10:
                    $performance_evalation = PerformanceProgramEvalation::where('evalation_id',$employee_id)
                        ->where('performance_program_id',$performance_program_id)
                        ->where('type_id',10)
                        ->first();
                    if ($performance_evalation)
                    {

                        $performance_form = PerformanceForm::where('performance_program_evalation_id',$performance_evalation->id)->sum('puan');
                        $deger = ($performance_form * $performance_program_type->puan)/100;
                        return number_format($deger,2,',','.');
                    }
                    else
                    {
                        return 0;
                    }
                    break;
                case 11:
                    $puan = ManagementPuan::where('performance_program_id',$performance_program_id)
                        ->where('employee_id',$employee_id)
                        ->first();
                    if (!$puan)
                    {
                        return 0 ;
                    }

                    $deger = ($puan->puan * $performance_program_type->puan)/100;
                    return number_format($deger,2,',','.');
                    break;
                case 12:
                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)->where('performance_program_id', $performance_program_id)->where('type_id',12)->get()->pluck('id')->toArray();
                    $toplamPuan = 0;
                    if ($evalation)
                    {
                        $degerlendirme_sayisi = count($evalation);
                        $sumPuan = PerformanceForm::whereIn('performance_program_evalation_id', $evalation)->sum('puan');
                        $toplamPuan = $sumPuan / $degerlendirme_sayisi;
                        $sonuc = ($toplamPuan * $performance_program_type->puan)/100;
                        return number_format($sonuc,'2',',','.');
                    }
                    else
                    {
                        return 'Değerlendime Yoktur';
                    }
                    break;
                case 13:
                    $program_types = PerformanceProgramType::where('performance_program_id', $performance_program_id)->get();
                    $count = 0;
                    $basari = 0;
                    foreach ($program_types as $type)
                    {
                        $deger = null;
                        if ($type->performance_type_id != 13)
                        {
                           $deger =  (float)$type->performance_type_puan($employee_id,$type->performance_program_id,$type->performance_type_id);

                           $target = PerformanceTarget::where('performance_program_id',$type->performance_program_id)->first();
                           if ($target)
                           {
                               $target_puan = PerformanceTargetService::where('performance_program_target_id',$target->id)
                                   ->where('performance_type_id',$type->performance_type_id)
                                   ->first();
                               if ($target_puan)
                               {
                                   $count ++;

                                   if ($deger >= $target_puan->target_puan)
                                   {
                                       $basari ++;
                                   }
                               }

                           }
                        }
                    }
                    if ($basari==0)
                    {
                        $basari = 1;
                    }
                    if ($count == 0)
                    {
                        $count = 1;
                    }
                    $hedef = ($basari * 100)/$count;
                    return number_format(($performance_program_type->puan * $hedef)/100,2,',','.');
                    break;




            }
        }
        else
        {
            return null;
        }






    }
    public function performance_type_puan2($employee_id,$performance_program_id,$type_id)
    {
        $performance_program_type = PerformanceProgramType::where('performance_program_id',$performance_program_id)
            ->where('performance_type_id',$type_id)->first();
        if (!empty($performance_program_type))
        {
            switch ($type_id) {
                case 1:
                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)->where('performance_program_id', $performance_program_id)->where('type_id',2)->first();
                    if ($evalation) {

                        $sumPuan = PerformanceForm::where('performance_program_evalation_id', $evalation->id)->sum('puan');
                        $sonuc = ($sumPuan * $performance_program_type->puan) / 100;

                        return $sonuc;
                    }
                    else
                    {
                        return 0;
                    }

                    break;
                case 2:


                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)->where('performance_program_id', $performance_program_id)->where('type_id',1)->get()->pluck('id')->toArray();
                    if ($evalation)
                    {
                        $degerlendirme_sayisi = count($evalation);

                        $sumPuan = PerformanceForm::whereIn('performance_program_evalation_id', $evalation)->sum('puan');
                        $sonuc = ($sumPuan / $degerlendirme_sayisi);
                        $sonuc  = ($performance_program_type->puan * $sonuc)/100;
                        return $sonuc;
                    }
                    else
                    {
                        return 0;
                    }
                    break;
                case 3:

                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)
                        ->where('employee_id',$employee_id)
                        ->where('performance_program_id', $performance_program_id)
                        ->where('type_id',3)
                        ->first();

                    if ($evalation)
                    {

                        $sumPuan = PerformanceForm::whereIn('performance_program_evalation_id', [$evalation->id])->sum('puan');

                        $sonuc = ($sumPuan * $performance_program_type->puan)/100;
                        return $sonuc;
                    }
                    else
                    {
                        return 0;
                    }
                    break;
                case 4:
                    $disciplines = PerformanceEmployeeDiscipline::where('performance_program_id',$performance_program_id)
                        ->where('employee_id',$employee_id)->count();

                    $info = array('0' =>'100','1'=>'50','2'=>'10');
                    switch ($disciplines)
                    {
                        case 0 :
                            $sonuc = (100 * $performance_program_type->puan)/100;
                            return $sonuc;
                            break;
                        case 1:
                            $sonuc = (50 * $performance_program_type->puan)/100;
                            return$sonuc;
                            break;
                        case 2:
                            $sonuc = (10 * $performance_program_type->puan)/100;
                            return $sonuc;
                            break;

                    }
                    if ($disciplines > 2 )
                    {
                        $sonuc = (10 * $performance_program_type->puan)/100;
                        return $sonuc;
                        break;
                    }
                    break;
                case 5;
                    $employeeInfo = EmployeePersonalInfo::where('employee_id',$employee_id)->first();

                    if (!empty($employeeInfo->completed_education) and !empty($performance_program_type->puan))
                    {
                        if ($employeeInfo->completed_education == 0 or $employeeInfo->completed_education == 1 )
                        {
                            $sonuc = (10 * $performance_program_type->puan)/100;
                            return $sonuc;
                        }
                        elseif($employeeInfo->completed_education == 2)
                        {
                            $sonuc = (30 * $performance_program_type->puan)/100;
                            return $sonuc;
                        }
                        elseif($employeeInfo->completed_education == 3)
                        {
                            $sonuc = (50 * $performance_program_type->puan)/100;
                            return $sonuc;
                        }
                        elseif($employeeInfo->completed_education == 4)
                        {
                            $sonuc = (70 * $performance_program_type->puan)/100;
                            return $sonuc;
                        }
                        elseif($employeeInfo->completed_education == 5)
                        {
                            $sonuc = (90 * $performance_program_type->puan)/100;
                            return $sonuc;
                        }
                        elseif($employeeInfo->completed_education == 6)
                        {
                            $sonuc = (100 * $performance_program_type->puan)/100;
                            return $sonuc;
                        }
                    }
                    else
                    {
                        return 0;
                    }

                    break;
                case 6:

                    $days = Discontinuty::where('employee_id',$employee_id)->where('time_type',0)->get()->sum('time');
                    $saat = Discontinuty::where('employee_id',$employee_id)->where('time_type',1)->get()->sum('time');
                    $dakika = Discontinuty::where('employee_id',$employee_id)->where('time_type',2)->get()->sum('time');
                    $toplam_saat_gun = $saat / 24 ;
                    $toplam_dakika_gun = $dakika / 1440;
                    $sonuc = $toplam_saat_gun + $toplam_dakika_gun + $days;

                    if ($sonuc < 5 )
                    {
                        $deger = (100 * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    elseif ($sonuc > 6 and $sonuc <= 10)
                    {
                        $deger = (70 * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    else
                    {
                        $deger = (10 * $performance_program_type->puan)/100;
                        return $deger;
                    }

                    break;
                case 7:
                    $employee = EmployeePersonalInfo::where('employee_id',$employee_id)->first();
                    if (!$employee)
                    {
                        return 0;
                    }
                    $puanlar = [];

                    if ($employee->home == 1)
                    {
                        $puanlar[] = 33;
                    }
                    if($employee->university == 1)
                    {
                        $puanlar[] = 34;
                    }
                    if($employee->disability_level == 1 or $employee->disability_level == 2  )
                    {
                        $puanlar[] = 33;
                    }

                    $sonuc_puan = array_sum($puanlar);
                    if (!empty($performance_program_type->puan))
                    {
                        $deger = ($sonuc_puan * $performance_program_type->puan)/100;
                    }
                    else
                    {
                        $deger = 0;
                    }

                    return $deger;
                    break;
                case 8:
                    $employee = Employee::find($employee_id);
                    $now_date = Carbon::now();
                    $date = Carbon::parse($employee->job_start_date);
                    $date = $date->diffInYears($now_date, false);
                    if ($date <= 1)
                    {
                        $deger = (20 * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    if ($date >= 1 and $date <= 3)
                    {
                        $deger = (40 * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    if ($date >= 4 and $date <= 5)
                    {
                        $deger = (60 * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    if ($date >= 5 and $date <= 5)
                    {
                        $deger = (80 * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    if ($date >= 6)
                    {
                        $deger = (100 * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    break;
                case 9:
                    $employees_languages = EmployeeLanguage::where('employee_id',$employee_id)->count();
                    if (!empty($performance_program_type->puan))
                    {
                        if ($employees_languages <=1)
                        {
                            $deger = (30 * $performance_program_type->puan)/100;
                            return $deger;
                        }
                        if ($employees_languages > 1 and $employees_languages <= 2 )
                        {
                            $deger = (60 * $performance_program_type->puan)/100;
                            return $deger;
                        }
                        if ($employees_languages > 2)
                        {
                            $deger = (100 * $performance_program_type->puan)/100;
                            return $deger;
                        }
                    }
                    else
                    {
                        return 0;
                    }

                    break;
                case 10:
                    $performance_evalation = PerformanceProgramEvalation::where('evalation_id',$employee_id)
                        ->where('performance_program_id',$performance_program_id)
                        ->where('type_id',10)
                        ->first();
                    if ($performance_evalation)
                    {

                        $performance_form = PerformanceForm::where('performance_program_evalation_id',$performance_evalation->id)->sum('puan');
                        $deger = ($performance_form * $performance_program_type->puan)/100;
                        return $deger;
                    }
                    else
                    {
                        return 0;
                    }
                    break;
                case 11:
                    $puan = ManagementPuan::where('performance_program_id',$performance_program_id)
                        ->where('employee_id',$employee_id)
                        ->first();
                    if (!$puan)
                    {
                        return 0 ;
                    }

                    $deger = ($puan->puan * $performance_program_type->puan)/100;
                    return $deger;
                    break;
                case 12:
                    $evalation = PerformanceProgramEvalation::where('evalation_id', $employee_id)->where('performance_program_id', $performance_program_id)->where('type_id',12)->get()->pluck('id')->toArray();
                    $toplamPuan = 0;
                    if ($evalation)
                    {
                        $degerlendirme_sayisi = count($evalation);
                        $sumPuan = PerformanceForm::whereIn('performance_program_evalation_id', $evalation)->sum('puan');
                        $toplamPuan = $sumPuan / $degerlendirme_sayisi;
                        $sonuc = ($toplamPuan * $performance_program_type->puan)/100;
                        return $sonuc;
                    }
                    else
                    {
                        return 0;
                    }
                    break;
                case 13:
                    $program_types = PerformanceProgramType::where('performance_program_id', $performance_program_id)->get();
                    $count = 0;
                    $basari = 0;
                    foreach ($program_types as $type)
                    {
                        $deger = null;
                        if ($type->performance_type_id != 13)
                        {
                            $deger =  (float)$type->performance_type_puan($employee_id,$type->performance_program_id,$type->performance_type_id);

                            $target = PerformanceTarget::where('performance_program_id',$type->performance_program_id)->first();
                            if ($target)
                            {
                                $target_puan = PerformanceTargetService::where('performance_program_target_id',$target->id)
                                    ->where('performance_type_id',$type->performance_type_id)
                                    ->first();
                                if ($target_puan)
                                {
                                    $count ++;

                                    if ($deger >= $target_puan->target_puan)
                                    {
                                        $basari ++;
                                    }
                                }

                            }
                        }
                    }
                    if ($basari==0)
                    {
                        $basari = 1;
                    }
                    if ($count == 0)
                    {
                        $count = 1;
                    }
                    $hedef = ($basari * 100)/$count;
                    return ($performance_program_type->puan * $hedef)/100;
                    break;




            }
        }
        else
        {
            return null;
        }






    }
    public function type_puan($performance_program_id,$type_id)
    {
        $performance_type = PerformanceProgramType::where('performance_type_id',$type_id)->where('performance_program_id',$performance_program_id)->first();
        $program = PerformanceProgram::find($performance_program_id);
        if (!$performance_type or !$program)
        {
            return 0;
        }
        return $performance_type->puan;

    }
}
