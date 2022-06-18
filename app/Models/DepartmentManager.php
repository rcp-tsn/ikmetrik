<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentManager extends Model
{
    protected $guarded;

    public function department()
    {
        return $this->hasOne('App\Models\Department','id','department_id');
    }
}
