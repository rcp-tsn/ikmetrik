<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    protected $guarded;
    protected $dates = ['start_date','job_start_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }

    public function department()
    {
        $employee = Employee::find($this->employee_id);
        if (!$employee)
        {
            return '';
        }

        $department = Department::where('id',$employee->department_id)->first();
        if ($department)
        {
            return $department->name;
        }
        else
        {
            return '';
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'create_user_id', 'id');
    }
}
