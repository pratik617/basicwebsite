<?php

namespace App\Model\Driver;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $table = "customer_feedback";
    public $incrementing = false;
    public $primaryKey = false;
}
