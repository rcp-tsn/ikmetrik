<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetrikConstant extends Model
{

    protected $guarded = [];
    protected $table = 'metrik_constants';
    protected $dates = [
        'overtime_date',
    ];

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\MetricConstant');
    }

}
