<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacketInventory extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get all of the owning inventorytable models.
     */
    public function inventorytable()
    {
        return $this->morphTo();
    }

    /**
     * Get the packet of the inventory.
     */
    public function packet()
    {
        return $this->belongsTo('App\Models\Packet');
    }
}
