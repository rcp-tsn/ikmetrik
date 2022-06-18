<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyJobQuestionService extends Model
{
    protected $guarded;
    protected $dates = ['date'];

    public function department()
    {
        return $this->hasOne('App\Models\Department','id','department_id');
    }
}
