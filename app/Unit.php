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
}
