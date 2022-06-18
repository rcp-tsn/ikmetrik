<?php

namespace App\Models;

use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HashingSlug;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
