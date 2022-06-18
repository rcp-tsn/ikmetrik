<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class PayrollNotification extends Model
{
    protected $guarded;
    protected $dates = ['date','created_at','updated_at'];

    public function employee()
    {
        $working = Employee::find($this->employee_id);
        if (!$working)
        {
            return ' ';
        }
        return $working->full_name;
    }
    public function employee_id()
    {
        $working = Employee::find($this->employee_id);
        if (!$working)
        {
            return ' ';
        }
        return $working->id;
    }
}
