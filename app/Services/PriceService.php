<?php
namespace App\Services;

use App\Order;
use Carbon\Carbon;

class PriceService
{

    public function getPrice($orderId)
    {
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
}
