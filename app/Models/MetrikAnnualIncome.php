<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetrikAnnualIncome extends Model
{

    protected $guarded = [];
    protected $table = 'metrik_annual_incomes';


    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

}
