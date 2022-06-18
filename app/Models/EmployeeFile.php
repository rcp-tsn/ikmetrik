<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFile extends Model
{
    protected $guarded;
    protected $dates = ['date','upload_date','accept_date'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }
    public function file_type()
    {
        return $this->belongsTo('App\Models\EmployeeFileType','file_type_id','id');
    }

    public function protest($employee_id)
    {
        $protest = EmployeeProtest::where('employee_file_id',$this->id)->where('employee_id',$employee_id)->first();
        if (!$protest)
        {
            return 0;
        }
        return $protest->id;
    }

}
