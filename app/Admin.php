<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'unit_id'
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }
}
