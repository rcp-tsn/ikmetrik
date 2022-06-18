<?php

namespace App\Models;
use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    use HashingSlug;
    protected $table = 'cities';
    protected $dates = [
        'accrual'
    ];
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

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }
}
