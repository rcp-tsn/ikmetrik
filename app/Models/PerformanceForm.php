<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceForm extends Model
{
    protected $guarded;

    public function question()
    {

        return $this->hasOne('App\Models\Question','id','question_id');
    }

}
