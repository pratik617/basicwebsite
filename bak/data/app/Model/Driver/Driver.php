<?php

namespace App\Model\Driver;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Authenticatable
{
	use Notifiable,HasApiTokens; //,SoftDeletes
    public $table = "drivers";
    public $primaryKey = "id";

    protected $hidden = [
        'password', 'remember_token','device_token','otp','driving_status'
    ];
   public function Vehicle_type()
   {
   	 return $this->hasMany('App\model\Vehicle_types','foreign_key');
   }

   public function Vehicle_Category()
   {
     return $this->hasMany('App\model\VehicleCategorys','foreign_key'); 
   }

  public function Country()
  {
    return $this->hasMany('App\Model\Country','foreign_key');
  }

}
