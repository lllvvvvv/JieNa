<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    //order状态  1:送货上门 2:自提 3:已提未支付 4:待上门回收 5:支付完成 6:废订单 7:下单未授权，等待授权 8:买断提前结束订单
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','billno','status','arrive_address','arrive_time','unit_id','price','boxes','freeze'];

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
