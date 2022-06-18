<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeclarationInfo extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $dates = [
        'birth_date'
    ];



}
