<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GainIncentive extends Model
{

    use SoftDeletes;

    protected $table = 'gain_incentives';
    protected $dates = [
        'accrual'
    ];

}
