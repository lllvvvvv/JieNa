<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HouseKeepCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->map(function($housekeep) {
            return [
                    'id'=>$housekeep->id,
                    'billno' => $housekeep->billno,
                    'price' => $housekeep->price,
                    'detailed_address' => $housekeep->detailed_address,
                    'appointment' => $housekeep->appointment,
                    'address' => $housekeep->unit->name,
                    'order_status' => $housekeep->order_status,
                    'user_id'=> $housekeep->user_id
            ];
        });
    }
}
