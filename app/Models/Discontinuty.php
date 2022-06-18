<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discontinuty extends Model
{
    protected $table = 'discontinuities';
    protected $guarded;
    protected $dates = ['date'];
}
