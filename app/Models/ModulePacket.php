<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePacket extends Model
{
    protected $table = 'module_packet';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the module that owns the module company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo('App\Models\Module');
    }

    /**
     * Get the company that owns the module company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function packet()
    {
        return $this->belongsTo('App\Models\Packet');
    }
}
