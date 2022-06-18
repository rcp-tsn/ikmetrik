<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacketPriceCompany extends Model
{
    protected $table = 'packet_company';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the packet that owns the packet company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function packet_price()
    {
        return $this->belongsTo('App\Models\PacketPrice');
    }

    /**
     * Get the company that owns the packet company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}
