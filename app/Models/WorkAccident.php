<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkAccident extends Model
{


    protected $table = 'work_accidents';
    protected $dates = [
        'kaza_tarihi',
        'bildirim_zamani',
    ];

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

}
