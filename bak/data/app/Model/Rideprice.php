<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rideprice extends Model
{
    use SoftDeletes;
    public $table = "ride_price";
    public $primaryKey = "id";
    protected $softDelete = true;
 
   public function Vehicle_type()
   {
   	 return $this->hasMany('App\model\Vehicle_types','foreign_key');
   }

   public function Vehicle_Category()
   {
     return $this->hasMany('App\model\VehicleCategorys','foreign_key'); 
   }


}
