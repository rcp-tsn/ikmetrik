<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{


    protected $table = 'model_has_roles';

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
