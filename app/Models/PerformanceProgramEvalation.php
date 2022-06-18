<?php

namespace App\Models;

use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class PerformanceProgramEvalation extends Model
{
    use HashingSlug;
    protected $guarded = [];


    public function employee()
    {

         return $this->hasOne(Employee::class,'id','employee_id');

    }
    public function subordinate()
    {
       return  $this->hasOne(Employee::class,'id','evalation_id');
    }
    public function performance_type()
    {
       return  $this->belongsTo('App\Models\PerformanceType','type_id','id');
    }
    public function sumPuan($evalation_id)
    {
        $performance_form = PerformanceForm::where('performance_program_evalation_id',$evalation_id)->sum('puan');
        if (!$performance_form)
        {
            return 0;
        }
        return number_format($performance_form,2,',','.');
    }
}
