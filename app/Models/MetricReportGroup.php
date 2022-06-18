<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetricReportGroup extends Model
{


    public function metric_reports()
    {
        return $this->hasMany(MetricReport::class);
    }

}
