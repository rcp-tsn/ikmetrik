<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePersonalInfo extends Model
{
    protected $guarded = [];
    protected $table = 'employee_personal_infos';
    protected $dates = [
        'birth_date'
    ];
}
