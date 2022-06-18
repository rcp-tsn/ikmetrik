<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkVizite extends Model
{


    protected $table = 'work_vizites';
    protected $dates = [
        'poliklinik_tarihi',
        'isbasi_tarihi',
    ];

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

}
