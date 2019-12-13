<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    protected $fillable = ['flow_id','notify_type','content'];
//json_decode($test->content)
}
