<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id','name','password','api_token'
    ];

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function getAuthIdentifier()
    {
        return $this->primaryKey;
    }
}
