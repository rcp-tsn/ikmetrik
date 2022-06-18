<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveIncentive extends Model
{



    protected $table = 'active_incentives';
    protected $dates = [
        'accrual',
        'start',
        'finish',
        'job_start'
    ];

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

}
