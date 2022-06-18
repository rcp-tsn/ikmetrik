<?php

namespace App\Models;

use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{

    use HashingSlug;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The modules that belong to the company.
     */
    public function modules()
    {
        return $this->belongsToMany('App\Models\Module', 'module_packet');
    }

    /**
     * Tests of the packet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testInventories()
    {
        return $this->hasMany('App\Models\PacketInventory')->where('inventorytable_type', 'App\Models\Test');
    }
}
