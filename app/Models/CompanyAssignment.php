<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CompanyAssignment extends Model
{


    protected $table = 'company_assignments';
    protected $fillable = [
        'sgk_company_id',
        'user_id'
    ];

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }


}
