<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiclecategory extends Model
{
    use SoftDeletes;
    public $table = "vehicle_categorys";
    public $primaryKey = "id";
    protected $softDelete = true; 

    public function vehicle_types()
    {
        return $this->hasMany('App\model\vehicle_types','foreign_key');
    }
}
