<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentityNotification extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $dates = [
        'ise_giris_tarihi',
        'isten_ayrilis_tarihi',


    ];



}
