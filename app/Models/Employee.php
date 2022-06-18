<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];
    protected $dates = ['job_start_date'];

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
    public function department()
    {
        return $this->belongsTo('App\Models\Department','department_id','id');
    }
    public function company()
    {
        return $this->belongsTo('App\Models\Company','company_id','id');
    }
    public function management_puan($id,$employee_id)
    {
        $puan =ManagementPuan::where('employee_id',$employee_id)
            ->where('performance_program_id',$id)
            ->first();
        if (!empty($puan->puan))
        {
            return $puan->puan;
        }
        else
        {
            return 0;
        }

        //return $this->hasOne('App\Models\ManagementPuan','employee_id','id');
    }
    public function working_title()
    {
        return $this->belongsTo('App\Models\WorkTitle','working_title_id','id');
    }
    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany','sgk_company_id','id');
    }
    public function employeeSalary()
    {
        return $this->hasOne('App\Models\EmployeeSalary','employee_id','id');
    }
    public function employee_personel()
    {
       return $this->hasOne('App\Models\EmployeePersonalInfo','employee_id','id');
    }

    public function employee_subordinate($id)
    {

        $subordinates = EmployeeSubordinate::where('employee_id',$id)->get()->pluck('subordinate_id')->toArray();

       if (count($subordinates) > 0)
       {
           $employee = Employee::whereIn('id',$subordinates)->get();
           return $employee;
       }
       else
       {
           return $employee = [];
       }
    }

    public function employee_ust($id,$name=false)
    {

        $employee = Employee::where('id',$id)->first();


        if ($name == false)
        {
            $employee_ust = Employee::where('id',$employee->top_manager_id)->first();

            if (!empty($employee_ust->avatar))
            {

                return $employee_ust->avatar;

            }
            else
            {
                return 'Tanımlı Değildir';
            }

        }
        elseif($name==true)
        {
            $employee_ust = Employee::where('id',$employee->top_manager_id)->first();
            if (!empty($employee_ust->first_name) and !empty($employee_ust->last_name))
            {
                return $employee_ust->first_name.' '.$employee_ust->last_name;
            }
            else
            {
                return null;
            }

        }
        else
        {
            return 'Üstü Yoktur Eklemek İçin Çalışanlardan Güncelleyin';
        }
    }
    public function completed_education($id)
    {
        $employee = EmployeePersonalInfo::where('employee_id',$id)->first();
        if (!empty($employee->completed_education))
        {
            return $employee->completed_education;
        }
        else
        {
            return 1;
        }

    }
    public function pozisyon()
    {

        return $this->belongsTo(Statu::class,'pozisyon_id','id');
    }
    public function scholl()
    {
       $university = UniversityPart::find($this->scholl);
       if ($university)
       {
           return $university->name;
       }
       else
       {
           return 'BOŞ';
       }
    }

    public function leaves()
    {
        return $this->hasOne('App\Models\EmployeeLeave','employee_id','id');
    }

    public function leaveYears()
    {
        $date = Carbon::now(config('app.tz'));
        $today_year = $date->year;



        $date2 = Carbon::parse($this->job_start_date);
        $first_year = $date2->year;

        $years= [];
        for($i = $first_year; $i <= $today_year; $i++) {
            $years[$i] = $i;
        }

        return $years;
    }
    public function getLeaveInfo($year)
    {

            return  EmployeeLeave::where('employee_id', $this->id)->where('start_date','LIKE','%'. $year .'%')
                ->where('leave_type',13)
                ->where('status','2')
                ->sum('days');


    }

    public function calculateLeaveKredi( $total_worked_day, $yas )
    {
        if ($total_worked_day < 360) {
            return 0;
        } else if ($yas >= 50) {
            return 20;
        }
        else
        {

        }

        if($total_worked_day >= 360 && $total_worked_day <= 1800) {
            return 14;
        } elseif($total_worked_day > 1800 && $total_worked_day <= 5400) {
            return 20;
        } else {
            return 26;
        }
    }

    public function getSeniorityData()
    {
        $bugun = Carbon::now(config('app.tz'));

        $array = explode(' ', $bugun);
        $gun = $array[0];


        if($this->job_end_date) {
            $job_finish = $this->job_end_date;
            $hakedis = $this->job_end_date;
        } else {
            $job_finish = $bugun->format('Y-m-d');
            $hakedis = $bugun->format('Y-m-d');

        }
        $seniority_data['yas'] = 0;
        $year = $bugun->format('Y');
        if (!isset($this->employee_personel->birth_date)) {
            $birth_year = '1900';

        } else {
            $dateOfBirth = $this->employee_personel->birth_date;
            $seniority_data['yas'] = Carbon::parse($dateOfBirth)->age;


            $birth_year = $this->employee_personel->birth_date->format('Y');
        }

        $seniority_data['job_finish'] = $job_finish;
        $seniority_data['hakedis'] = $hakedis;
        $seniority_data['year'] = $year;
        $seniority_data['birth_year'] = $birth_year;
        $seniority_data['age'] = $year - $birth_year;
        $seniority_data['first_day_of_year'] = $bugun->format('Y-01-01');
        $date1 = Carbon::parse($seniority_data['first_day_of_year']);
        $diff = $date1->diffInDays($bugun);



        $seniority_data['job_start_date'] = $this->job_start_date->format('Y-m-d');
        $seniority_data['month'] = $bugun->format('m');
        $seniority_data['start_month'] = $this->job_start_date->format('m');


        $date_job_start_date = Carbon::parse($seniority_data['job_start_date']);

        $date2 = Carbon::parse($seniority_data['job_start_date']);


        $diff2 = $date2->diffInDays($bugun);

        $yil_farki = $date2->diffInYears($bugun);

        $seniority_data['worked_day_count'] = $diff2;
        $seniority_data['worked_year_count'] = $yil_farki;

        $leave = [];
        for($i = 1; $i <= $yil_farki; $i++) {
            $leave[] = $this->calculateLeaveKredi(360 * $i, $seniority_data['yas']);
        }
        $seniority_data['year_by_leave'] = $leave;
        $seniority_data['total_leave_credit'] = array_sum($leave);
        $seniority_data['used_leave_credit'] = $this->usedLeave();
        $seniority_data['remaining_leave_credit'] = $seniority_data['total_leave_credit'] - $seniority_data['used_leave_credit'];
        return $seniority_data;
    }

    public function usedLeave( )
    {
        $total = 0;

            foreach ($this->leaves2 as $leave) {

                if ($leave->leave_type == 13) {
                    $total += $leave->days;
                }

            }

            return $total;

    }

    public function leaves2()
    {
        return $this->hasMany('App\Models\EmployeeLeave');
    }


}
