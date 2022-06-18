<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceTarget extends Model
{
    protected $guarded;
    public function performance()
    {
        return $this->hasOne('App\Models\PerformanceProgram','id','performance_program_id');
    }
    public function count()
    {
        $degerler = PerformanceTargetService::where('performance_program_target_id',$this->id)->count();
        return $degerler;
    }
}

