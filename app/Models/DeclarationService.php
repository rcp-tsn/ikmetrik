<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeclarationService extends Model
{

    use SoftDeletes;

    protected $table = 'declaration_services';
    protected $fillable = [
        'declaration_id',
        'tck',
        'sg_sicil_no',
        'isim',
        'soyisim',
        'ucret_tl',
        'ikramiye_tl',
        'gun',
        'job_start',
        'meslek_kod',
        'request',
    ];

    protected $dates = [
        'job_start',
    ];

    public function incitements()
    {
        $id = session()->get('selectedCompany')['id'];
        // dd(Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'));
        return $this->hasMany(Incentive::class, 'tck', 'tck')
            ->where("finish", '>', Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'))
            ->where("start", '<=', Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'))
            ->where('filter_status', 1)
            ->where('sgk_company_id', $id)
            ->where(function ($qq) {
                $qq->orWhereNull('job_finish')
                    ->orWhere('job_finish', '>=', Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'));
            })
            ->orderBy("finish", "DESC");
    }

    public function calisanTanimlandimi($sgk_company_id)
    {
        $q = Incentive::where('sgk_company_id', $sgk_company_id)->where('tck', $this->tck)->first();
        if ($q) {
            return true;
        } else {

                return false;

        }
    }


    public function declaration()
    {
        return $this->hasMany(Declaration::class, 'id', 'declaration_id');
    }
}
