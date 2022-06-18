<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollProtest extends Model
{
    protected $guarded;
    protected $dates=['date','created_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }
}
