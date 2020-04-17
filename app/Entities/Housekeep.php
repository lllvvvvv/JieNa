<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Housekeep.
 *
 * @package namespace App\Entities;
 */
class Housekeep extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','price','billno','service_type','specific_type','detailed_address','appointment','pay_time','unit_id'
    ,'order_status','user_id'];

    public function Unit()
    {
        return $this->belongsTo('App\Unit','unit_id','id');
    }
}
