<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyHasDocument extends Model
{
    protected $guarded;

    public function document()
    {
        return $this->hasOne('App\Models\Document','id','document_id');
    }
}
