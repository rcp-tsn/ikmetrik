<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacketCompany extends Model
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
    public function packet()
    {
        return $this->hasMany('App\Models\Packet');
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
