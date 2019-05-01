<?php 
namespace App\Helper;

use Illuminate\Database\Eloquent\Helper;
use App\Helper\Exceptions;
use App\Model\Configtbl;

class ConfigHelper
{
	public function radius()
	{
		try {
			$radius_default = 3;
			$radius = Configtbl::get()->first()->radius;
			if($radius!=""){
				return $radius;
			} else{
				return $radius_default;
			}
		} catch (Exception $e) {
			Exceptions::exception($e);
		}
	}

	public function cabCount()
	{
		try {
			$count_default = 5;
			$count = Configtbl::get()->first()->near_count;
			if($count!=""){
				return $count;
			} else{
				return $count_default;
			}
		} catch (Exception $e) {
			Exceptions::exception($e);
		}	
	}

	public function mapKey()
	{
		try {
			$mapkey = Configtbl::get()->first()->map_key;
			if($mapkey!=""){
				return $mapkey;
			} else{
				return "";
			}
		} catch (Exception $e) {
			Exceptions::exception($e);	
		}
	}
}