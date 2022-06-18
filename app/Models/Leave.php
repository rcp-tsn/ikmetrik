<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $guarded;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }
    public function employee_leave()
    {
        return $this->belongsTo('App\Models\EmployeeLeave', 'employee_leave_id', 'id');
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
}
