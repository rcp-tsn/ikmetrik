<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CustomerEmail extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }

    public function hasEmail()
    {

        $user = User::where('email', mb_strtolower($this->email))->first();
        if ($user) {
            return '<span class="label label-sm label-danger label-inline mr-2">VAR</span>';
        } else {
            return '<span class="label label-sm label-success label-inline mr-2">MÜSAİT</span>';
        }
    }
}
