<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSubordinate extends Model
{
    protected $guarded;
    protected $dates = ['date'];
    public function employee()
    {
     return    $this->hasOne('App\Models\Employee','id','subordinate_id');
    }
    public function department()
    {
        $employee = Employee::where('id',$this->subordinate_id)->first();
        if (!$employee)
        {
            return 'Departman Tanımlı Değil';
        }
        $department = Department::where('id',$employee->department_id)->first();
        return $department->name;

    }
}
