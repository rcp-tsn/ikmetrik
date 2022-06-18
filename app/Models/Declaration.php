<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Declaration extends Model
{

    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'declarations';

    public function services()
    {

        return $this->hasMany('App\Models\DeclarationService');
    }

    public function services_norequest()
    {
        return $this->hasMany('App\Models\DeclarationService')->where("request", 0);
    }

    public function company_self()
    {
        return $this->belongsTo('App\Models\SgkCompany');
    }


    public function declaration_service()
    {
        return $this->hasMany(DeclarationService::class, 'id', 'declaration_id');
    }

}
