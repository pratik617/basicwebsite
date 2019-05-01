<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Vehicletype extends Model
{
    use SoftDeletes;
    public $table = "vehicle_types";
    public $primaryKey = "id";
    protected $softDelete = true;

    public function category()
    {
    	return $this->hasMany('App\Model\Vehiclecategory','vehicle_type_id','id')->where('status','=','active');
    }
}
