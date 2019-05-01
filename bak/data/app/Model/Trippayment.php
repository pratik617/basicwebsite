<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Trippayment extends Model
{
    public $table = "trip_payment_log";
    public $primaryKey = false;
    public $incrementing = false;
}