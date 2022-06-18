<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetrikEducationalCost extends Model
{

    protected $guarded = [];
    protected $table = 'metrik_educational_costs';


    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

}
