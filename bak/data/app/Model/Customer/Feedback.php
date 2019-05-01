<?php

namespace App\Model\Customer;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $table = "driver_feedback";
    public $incrementing = false;
    public $primaryKey = false;
}
