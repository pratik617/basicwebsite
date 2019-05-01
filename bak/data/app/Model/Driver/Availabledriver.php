<?php

namespace App\Model\Driver;

use Illuminate\Database\Eloquent\Model;

class Availabledriver extends Model
{
    public $table = "available_drivers";
    public $primaryKey = "id";
    public $timestamps = false;

     protected $fillable = [
	    'driver_id', 'latitude','longitude'
	  ];
}
