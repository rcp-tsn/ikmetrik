<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeciciIncentive extends Model
{

    use SoftDeletes;

    protected $table = 'gecici_incentives';
    protected $dates = [
        'start',
        'finish',
        'job_start',
        'job_finish'
    ];

    protected $guarded = [];

}
