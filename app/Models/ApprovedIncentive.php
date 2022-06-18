<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApprovedIncentive extends Model
{

    use SoftDeletes;

    protected $table = 'approved_incentives';

    public function incentive_services()
    {
        return $this->hasMany(IncentiveService::class, 'approved_incentive_id', 'id')
            ->orderBy("created_at", "DESC");
    }

    public function incentive_services_history()
    {
        return $this->hasMany(IncentiveService::class, 'approved_incentive_id', 'id')
            ->where('history_request', 0)
            ->orderBy("created_at", "DESC");
    }


    public function company_self()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

    public function personelCount($sgk_company_id)
    {
        $personel_counts = 0;
        $accrual = $this->accrual;
        $declarations = Declaration::where('sgk_company_id', $sgk_company_id)->where('declarations_date', $accrual)->get();
        foreach($declarations as $declaration) {
            $personel_counts += DeclarationService::where('declaration_id', $declaration->id)->count();
        }
        return $personel_counts;
    }

    public function yararlanmisPersonelSayisi($sgk_company_id, $law)
    {
        $incentive_service_counts = 0;
        $accrual = $this->accrual;
        $approved_incentives = ApprovedIncentive::where('sgk_company_id', $sgk_company_id)->where('law_no', $law)->where('accrual', $accrual)->get();
        foreach($approved_incentives as $approved_incentive) {
            $incentive_service_counts += IncentiveService::where('approved_incentive_id', $approved_incentive->id)->count();
        }
        return $incentive_service_counts;
    }
}
