<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumIncentive extends Model
{



    protected $table = 'sum_incentives';
    protected $dates = [
        'accrual'
    ];

    public function sgk_company()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }

    public function personelCount($sgk_company_id, $law)
    {
        if ($law == 7103) {
            $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->whereIn('kanun', [7103,17103,27103])->where('accrual', $this->accrual)->first();
        } else {
            $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->where('kanun', $law)->where('accrual', $this->accrual)->first();
        }


       if ($value) {
           return $value->calisan_sayisi;
       }

       return '';
    }

    public function yararlanabilen($sgk_company_id, $law)
    {
       if ($law == 7103) {
           $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->whereIn('kanun', [7103,17103,27103])->where('accrual', $this->accrual)->first();
       } else {
           $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->where('kanun', $law)->where('accrual', $this->accrual)->first();
       }

       if ($value) {
           return $value->yararlanabilen;
       }

       return '';
    }

    public function toplamTesviktutari($sgk_company_id, $law)
    {
        if ($law == 7103) {
            $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->whereIn('kanun', [7103,17103,27103])->where('accrual', $this->accrual)->first();
        } else {
            $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->where('kanun', $law)->where('accrual', $this->accrual)->first();
        }

       if ($value) {
           return $value->toplam_tutar;
       }

       return '';
    }

    public function yararlanmis($sgk_company_id, $law)
    {
        if ($law == 7103) {
            $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->whereIn('kanun', [7103,17103,27103])->where('accrual', $this->accrual)->first();
        } else {
            $value = SumIncentive::where('sgk_company_id', $sgk_company_id)->where('kanun', $law)->where('accrual', $this->accrual)->first();
        }


       if ($value) {
           return $value->yararlanilmis;
       }

       return '';
    }

}
