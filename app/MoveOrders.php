<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoveOrders extends Model
{

    protected $fillable = ['begin_address','finish_address','phone','price','car_type','user_id','driver_id'
    ,'order_type','car_type','moveno','appointment'];

    public function User() {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function scopeTime($query,$begin,$end)
{
    return $query->with(['user'])->where('created_at','>=',$begin)
        ->where('created_at','<=',$end)
        ->paginate(5);
}
}
