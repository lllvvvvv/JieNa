<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['status','unit_id','box_type'];
    public $timestamps = false;

    public function Unit()
    {
        return $this->belongsTo('Unit','id');
    }

    public function Order()
    {
        return $this->belongsTo('App\Order');
    }


}
