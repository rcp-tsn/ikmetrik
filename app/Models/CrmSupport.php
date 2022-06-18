<?php

namespace App\Models;
use App\Helpers\HashingSlug;
use Illuminate\Database\Eloquent\Model;

class CrmSupport extends Model
{

    use HashingSlug;
    protected $table = 'crm_supports';

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
    protected $fillable = ['contact_by', 'is_customer', 'company', 'name','email','phone', 'message', 'ip', 'status'];
    protected $dates = [
        'created_at'
    ];
}
