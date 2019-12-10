<?php
namespace App\Services;

use App\Box;
use App\BoxType;
use App\Order;
use Carbon\Carbon;

class PriceService
{

    public function getPrice($orderId)
    {
        //一块钱4小时,一天最多五块钱
        $boxes = Order::where('billno',$orderId)->first()->Boxes()->get();
        $amount = 0;
        foreach ($boxes as $box)
        {
            $price = $box->Price()->first()->price;
            $amount +=$price;
        }
        return $amount;
    }

    public function timeDifference($date)
    {
        if ($date) {
            $carbon = carbon::parse($date);
            $hour = (new Carbon)->diffInHours($carbon) + 1;
            return $hour;
        }
        else{
            return 0;
        }
    }

    public function timeCount($begin_time,$end_time = null)
    {
        if ($end_time == null)
        {
            $end_time = Carbon::now();
        }
        $days = carbon::parse($begin_time)->startOfDay()->diffInDays($end_time)+1;
        $target = 1;
        switch ($days)
        {
            case 1:
                $free = carbon::parse($begin_time)->diffInMinutes($end_time);
                if ($free <= 14)
                {
                    $target = 0;
                    break;
                }
                $target =$this->hoursCount(carbon::parse($begin_time)->diffInHours($end_time)+1);
                break;
            case 2:
                $first_end = carbon::parse($begin_time)->endOfDay();
                $first_hour = $this->hoursCount(carbon::parse($begin_time)->diffInHours($first_end)+1);
                $second_begin = carbon::parse($begin_time)->addDays(1)->startOfDay();
                $second_end = carbon::parse($end_time);
                $second_hour = $this->hoursCount($second_begin->diffInHours($second_end)+1);
                $target= $first_hour+$second_hour;
                break;
            default:
                $first_end = carbon::parse($begin_time)->endOfDay();
                $first_target = $this->hoursCount(carbon::parse($begin_time)->diffInHours($first_end)+1);
                $second_begin = carbon::parse($end_time)->startOfDay();
                $second_end = carbon::parse($end_time);
                $second_hour = $this->hoursCount($second_begin->diffInHours($second_end)+1);
                $days = ceil((($days-2)*20/4));
                $target = $first_target+$second_hour+$days;

        }

        return $target;
    }

    public function hoursCount($hour)
    {
        if ($hour>20)
        {
            $hour = 20;
        }
        $target = ceil($hour/4);
        return $target;
    }


    //获取箱体押金总和
    public static function getBoxDeposit($boxes)
    {
        $price = 0;
        foreach ($boxes as $box)
        {
            $deposit = BoxType::where('box_type',$box['box_type'])->first()->deposit;
            $deposit = $deposit*$box['box_count'];
            $price += $deposit;
        }
        return $price;
    }

}
