<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationChart extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user that owns the organization chart.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get sub chart
     *
     * @return mixed
     */
    public function subCharts()
    {
        return OrganizationChart::where('company_id', $this->company_id)->where('parent_id', $this->user_id)
                         ->get();
    }
}
