<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetrikAllocatedTime extends Model
{

    protected $guarded = [];
    protected $table = 'metrik_allocated_times';


    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

}
