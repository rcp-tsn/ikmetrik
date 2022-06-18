<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceEmployeeDiscipline extends Model
{
    protected $guarded;
    protected $dates=['discipline_date'];

    public function employee()
    {
       return  $this->belongsTo('App\Models\Employee','employee_id','id');
    }
}
