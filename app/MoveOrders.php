<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoveOrders extends Model
{
    protected $fillable = ['begin_address','finish_address','phone','price','car_type','user_id','driver_id'
    ,'order_type','car_type','moveno','appointment'];

}
