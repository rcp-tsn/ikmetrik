<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LeakIncentiveService;

class LeakApprovedIncentive extends Model
{
    protected $table = 'leak_approved_incentives';
    protected $guarded = [];

    public function declaration()
    {
        return $this->hasMany(Declaration::class, 'id', 'declaration_id');
    }

    public function leak_incentive_services()
    {
        return $this->hasMany(LeakIncentiveService::class, 'approved_incentive_id', 'id');
    }

    public function incentive_services_history()
    {
        return $this->hasMany(LeakIncentiveService::class, 'approved_incentive_id', 'id')
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
        $approved_incentives = LeakApprovedIncentive::where('sgk_company_id', $sgk_company_id)->where('law_no', $law)->where('accrual', $accrual)->get();
        foreach($approved_incentives as $approved_incentive) {
            $incentive_service_counts += LeakIncentiveService::where('approved_incentive_id', $approved_incentive->id)->count();
        }
        return $incentive_service_counts;
    }
}
