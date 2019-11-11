<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['id'];

//    public function Boxs() {
//        return $this->belongsToMany('App\Box','order_box','order_id','box_id');
//    }

    public function Boxes() {
        return $this->hasMany('App\Box');
    }

    public function User() {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function Unit()
    {
        return $this->belongsTo('App\Unit','unit_id','id');
    }
}
