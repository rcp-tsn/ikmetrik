<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $fillable = ['from', 'to', 'message', 'is_read'];

    /**
     * A message belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo('App\User', 'from');
    }

    public function toUser()
    {
        return $this->belongsTo('App\User', 'to');
    }
}
