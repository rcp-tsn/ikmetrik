<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetricReport extends Model
{

    public function metric_report_group()
    {
        return $this->belongsTo('App\Models\MetricReportGroup');
    }
}
