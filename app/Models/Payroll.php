<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Payroll extends Model
{
    protected $guarded;
    protected $dates=['date'];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function payroll_services()
    {
        return $this->hasMany(PayrollService::class,'payroll_id','id');
    }

    protected $fillable = [
        'created_at', 'updated_at', 'id','company_id','sms_status','sgk_company_id','date','create_user_id','employee_count'
    ];

}
