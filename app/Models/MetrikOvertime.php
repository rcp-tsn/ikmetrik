<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetrikOvertime extends Model
{

    protected $guarded = [];
    protected $table = 'metrik_overtimes';
    protected $dates = [
        'overtime_date',
    ];

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

}
