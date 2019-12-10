<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name'];

    public function Boxes()
    {
        return $this->hasMany('App\Box','unit_id');
    }

    public function Orders()
    {
        return $this->hasOne('App\Order','unit_id');
    }

    public function admins()
    {
        return $this->hasMany('App\Admin','unit_id');
    }
}
