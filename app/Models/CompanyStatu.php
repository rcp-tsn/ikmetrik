<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStatu extends Model
{
    protected $guarded;

    public function statu()
    {
       $ids = explode(',',$this->status_id);

       $status = Statu::whereIn('id',$ids)->get()->pluck('name')->toArray();
       

      return  $names = implode(',',$status);


    }
}
