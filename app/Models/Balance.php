<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the company that owns the balance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the adder user that owns the balance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adder_user()
    {
        return $this->belongsTo('App\Models\User', 'adder_user_id');
    }
}
