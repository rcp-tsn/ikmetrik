<?php

namespace App\Models;
use App\Helpers\HashingSlug;
use App\Helpers\ImageHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Company extends Model
{

    use HashingSlug;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $dates = [
        'start_date'
    ];

    /**
     * Get the users for the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * The modules that belong to the company.
     */

    /**
     * The packets that belong to the company.
     */
    public function packets($type = null)
    {
        return $this->hasMany('App\Models\PacketCompany')
            ->where('status', 1);
    }

    /**
     * Package inventories of the company
     *
     * @return mixed
     */
    public function packetInventories()
    {
        $companyPacketIds = $this->packets->pluck('id')->toArray();

        return PacketInventory::whereHas('packet', function ($q) use ($companyPacketIds) {
            $q->whereIn('id', $companyPacketIds);
        })->groupBy('inventorytable_id')->get();
    }

    public function modules()
    {
        return $this->belongsToMany('App\Models\Module', 'module_company');
    }


    public function parent()
    {
        return $this->belongsTo('company','company_id');
    }


    /**
     * Get the billing information record associated with the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billingInformation()
    {
        return $this->hasOne('App\Models\BillingInformation')->withDefault(function() {
            return new BillingInformation();
        });
    }


    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        $this->name;
    }


    /**
     * Companies logo url
     *
     * @return string
     */
    public function getPictureLinkAttribute()
    {
        if (! $this->picture) {
            $imageHelper = new ImageHelper();
            $imageHelper->createCompanyImage($this);
        }

        return $this->picture ? asset($this->picture) : asset('assets/images/no-image.jpeg');
    }

    public function getTotalEmployee()
    {
        $total_staff = 0;
        $sgk_companies = SgkCompany::where('company_id', Auth::user()->company_id)->select('id')->get();
        foreach($sgk_companies as $sgk_company) {
            $dates = ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->orderBy('accrual', 'DESC')->first();
            if ($dates) {
                $total_staff += ApprovedIncentive::where('sgk_company_id', $sgk_company->id)->where('accrual', $dates->accrual)->sum('total_staff');
            } else {
                $total_staff += 0;
            }

        }
        return $total_staff;

    }

    public function getKaza($type = 'toplam')
    {
        $ym = Carbon::now()->format('Y-m');
        $toplam_kaza = 0;
        $total_aydaki_kaza = 0;
        $sgk_companies = SgkCompany::where('company_id', Auth::user()->company_id)->select('id')->get();
        foreach($sgk_companies as $sgk_company) {
            $toplam_kaza += WorkAccident::where('sgk_company_id', $sgk_company->id)->sum('kisi_sayisi');
            $total_aydaki_kaza += WorkAccident::where('sgk_company_id', $sgk_company->id)->where('kaza_tarihi', 'LIKE',$ym)->sum('kisi_sayisi');
        }
        if ($type == 'toplam') {
            return $toplam_kaza;
        } else {
           return $total_aydaki_kaza;
        }

    }

    public function getRapor($type = 'toplam')
    {
        $ym = Carbon::now()->format('Y-m');
        $toplam_rapor = 0;
        $total_aydaki_rapor = 0;
        $sgk_companies = SgkCompany::where('company_id', Auth::user()->company_id)->select('id')->get();
        foreach($sgk_companies as $sgk_company) {
            $toplam_rapor += WorkVizite::where('sgk_company_id', $sgk_company->id)->count();
            $total_aydaki_rapor += WorkVizite::where('sgk_company_id', $sgk_company->id)->where('poliklinik_tarihi', 'LIKE',$ym)->count();
        }
        if ($type == 'toplam') {
            return $toplam_rapor;
        } else {
            return $total_aydaki_rapor;
        }

    }

    public function getPrice($staff_count = 0)
    {
        $packet = PacketPrice::where('type', $this->packet_price_type)->where('max_employee', '>=', $staff_count)->orderBy('max_employee', 'ASC')->first();
        if ($packet) {
            return $staff_count * $packet->price;
        }
        return -1;
    }

    public function getStafPrice($staff_count = 0)
    {
        $packet = PacketPrice::where('type', $this->packet_price_type)->where('max_employee', '>=', $staff_count)->orderBy('max_employee', 'ASC')->first();
        if ($packet) {
            return $packet->price;
        }
        return -1;

    }

}
