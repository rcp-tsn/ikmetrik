<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceProgram extends Model
{
    protected $guarded;
    protected $dates = ['start_date','finish_date','target_start_date','target_finish_date'];

    public function applicantCount($id)
    {
       return  PerformanceApplicant::where('performance_program_id',$id)->count();
    }

    public function applicantAvatar($id)
    {
        $applicants =  PerformanceApplicant::where('performance_program_id',$id)->get()->pluck('employee_id');

        if (count($applicants)  > 0)
        {
            $applicantAvatar = Employee::whereIn('id',$applicants)->get()->pluck('full_name','avatar')->toArray();
            return $applicantAvatar;

        }
        else
        {
            return  null;
        }

    }
}
