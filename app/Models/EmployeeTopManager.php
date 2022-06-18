<?php

namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;

class EmployeeTopManager extends Model
{
    protected $guarded;

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }

    public function user()
    {
        $employee = Employee::find($this->manager_id);
        if (!$employee)
        {
            return 0;
        }
        $user = User::where('employee_id',$employee->id)->first();
        if (!$user)
        {
            return 0;
        }
        return $user->name;
    }

    public function userId()
    {
        $employee = Employee::find($this->manager_id);
        if (!$employee)
        {
            return 0;
        }
        $user = User::where('employee_id',$employee->id)->first();
        if (!$user)
        {
            return 0;
        }
        return $user->id;
    }
}
