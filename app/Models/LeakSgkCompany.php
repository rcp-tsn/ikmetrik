<?php

namespace App\Models;

use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class LeakSgkCompany extends Model
{

    use HashingSlug;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $dates = [
        'created_at',
        'founded'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function getHakedis($accrual)
    {
        return GainIncentive::where('sgk_company_id', $this->id)->where('accrual', $accrual)->orderBy('accrual', 'DESC')->groupBy('accrual')->selectRaw('sum(law_6111) as total_law_6111, sum(law_27103) as total_law_27103, sum(law_7252) as total_law_7252, accrual')->first();
    }

}
