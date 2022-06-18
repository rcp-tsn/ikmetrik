<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncentiveService extends Model
{

    use SoftDeletes;

    protected $table = 'incentive_services';
    protected $dates = [
        'job_start',
        'job_finish'
    ];
    public function incitements()
    {
        return $this->hasMany(Incentive::class, 'tck', 'tck')
            ->where('filter_status', 1)
            ->orderBy("finish", "DESC");
    }

}
