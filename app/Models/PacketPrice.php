<?php

namespace App\Models;

use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class PacketPrice extends Model
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
}
