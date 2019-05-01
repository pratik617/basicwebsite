<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxe extends Model
{
    use SoftDeletes;
    public $table = "taxes";
    public $primaryKey = "id";
    protected $softDelete = true;

    public static function getTaxes($id){
    	if(Taxe::where('country_id',$id)->exists()){
    		$taxes = Taxe::select('id','country_id','tax_name','percent')->where('country_id',$id)->get();
    		return $taxes;
    	} else{
    		return "notax";
    	}
    }
    public function Country()
    {
        return $this->hasMany('App\Country','foreign_key');
    }
}
