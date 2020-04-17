<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const GET_STATUS_ONESELF = 2;  //用户自己取箱
    const GET_STATUS_ADMIN = 1;   //管理员上门送箱
    const BACK_STATUS_ONESELF = 2; //用户自己还箱
    const BACK_STATUS_ADMIN = 1; //管理员上门收箱

    //order状态  1:送货上门 2:自提 3:已提未支付 4:待上门回收 5:支付完成 6:废订单 7:下单未授权，等待授权 8:买断提前结束订单
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','billno','status','arrive_address','arrive_time','unit_id','price','boxes','freeze','get_status','back_status'];
//    protected $appends = ['get_type'];

    public static $getStatusMap = [
        self::GET_STATUS_ONESELF => '自取',
        self::GET_STATUS_ADMIN => '送箱',
    ];

    public static $backStatusMap = [
        self::BACK_STATUS_ONESELF => '自还',
        self::BACK_STATUS_ADMIN => '取箱',
    ];


    public function Boxs() {
        return $this->belongsToMany('App\Box','order_box','order_id','box_id');
    }

//    public function getGetTypeAttribute()
//    {
//        return Order::$getStatusMap[$this->get_status];
//    }

    public function getGetStatusAttribute($value)
    {
        return Order::$getStatusMap[$value];
    }

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

    public function scopeTime($query,$begin,$end)
    {
        return $query->with('User')->where('created_at','>=',$begin)
            ->where('created_at','<=',$end);
    }

}
