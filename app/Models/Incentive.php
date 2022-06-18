<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incentive extends Model
{

    use SoftDeletes;

    protected $table = 'incentives';


    public function services()
    {

        return $this->hasMany('App\Models\DeclarationService');
    }

    public function declaration_service()
    {
        $date = date('Y-m', strtotime('-1 months'));
        $firstDayDate = $date . "-01";
        //yüklenen en son  excellerini getiriyor
        $sgk_company = getSgkCompany();
        $declarations = Declaration::where('sgk_company_id',$sgk_company->id)->where('declarations_date',
        $firstDayDate)->pluck('id');

        return $this->hasMany(DeclarationService::class, 'tck', 'tck')
            ->whereIn('declaration_id',$declarations);
    }

}
