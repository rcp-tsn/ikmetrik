<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollService extends Model
{
    protected $guarded;

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }
    public function payroll()
    {
        return $this->belongsTo(Payroll::class,'payroll_id','id');
    }
    public function protest($employee_id)
    {
        $protest = PayrollProtest::where('payroll_id',$this->payroll_id)->where('employee_id',$employee_id)->first();
        if (!$protest)
        {
            return 0;
        }
        return $protest->id;
    }
}
