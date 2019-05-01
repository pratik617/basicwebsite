<?php

namespace App\Model\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tripdetail extends Model
{
    use SoftDeletes;
    public $table = "trip_details";
    public $primaryKey = "key";
    protected $softDelete = true; 

    public $incrementing = false;

    // protected $dates = ['retain_time','pickup_time','drop_time'];
}
