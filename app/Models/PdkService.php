<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdkService extends Model
{
    protected $guarded;

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }
    public function pdks()
    {
        return $this->belongsTo(Pdk::class,'pdk_id','id');
    }
    public function protest($employee_id)
    {
        $protest = PdkProtest::where('pdk_id',$this->pdk_id)->where('employee_id',$employee_id)->first();
        if (!$protest)
        {
            return 0;
        }
        return $protest->id;
    }
}
