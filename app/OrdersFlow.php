<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersFlow extends Model
{

    protected $table = 'orders_flow';
    //1:冻结
    //2:支付
    //3:解冻
    protected $fillable = ['flow_id','billno','user_id','price','type'];
}
