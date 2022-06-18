<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollCalender extends Model
{
    protected $guarded;
    protected $dates=['upload_date','sms_date','accept_date'];

    public function employee()
    {
        $payroll_service = PayrollService::find($this->payroll_service_id);
        if (!$payroll_service)
        {
            return 'İsim Bulunamadı';
        }
        $employee = Employee::where('id',$payroll_service->employee_id)->first();
        if (!empty($employee->full_name))
        {
            return $employee->full_name;
        }
        return 'İsim Bulunamadı';
    }
}
