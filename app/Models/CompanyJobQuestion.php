<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyJobQuestion extends Model
{
    protected $guarded;
    protected $dates = ['date'];

    public function department()
    {
        return $this->hasOne('App\Models\Department','id','department_id');
    }
    public function sgk_company()
    {
        return $this->hasOne('App\Models\SgkCompany','id','sgk_company_id');
    }
}
