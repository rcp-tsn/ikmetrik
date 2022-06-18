<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeakIncentive extends Model
{
    use SoftDeletes;

    protected $table = 'leak_incentives';


    public function services()
    {

        return $this->hasMany('App\Models\DeclarationService');
    }

    public function incentive_service()
    {

        $sgk_company = getSgkCompany();
        $approveds = LeakApprovedIncentive::where('sgk_company_id',$sgk_company->id)->
        pluck('id');
        $date = session()->get('date');
        return $this->hasMany(LeakIncentiveService::class, 'tck', 'tck')
            ->whereIn('approved_incentive_id',$approveds)
            ->where('accrual',$date);
    }


}
