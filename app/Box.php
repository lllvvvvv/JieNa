<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['status','unit_id','box_type'];

    public function Unit()
    {
        return $this->belongsTo('Unit','id');
    }

    public function Order()
    {
        return $this->belongsTo('App\Order');
    }

    public function Price()
    {
        return $this->hasOne('App\BoxType','box_type','box_type');
    }

}
