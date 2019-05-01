<?php 
namespace App\Helper;

use Illuminate\Database\Eloquent\Helper;
use App\Helper\Exceptions;

class TimeHelper
{
	public static function google_plus_time($google_time,$current_time="",$format="")
	{
		try {
			$plus_time = "";
			$current_time = $current_time?$current_time:date('Y-m-d H:i:s');
			$format = $format?$format:'Y-m-d H:i:s';
			$fare_duration_time = explode(" ",$google_time);
			foreach ($fare_duration_time as $fare_key => $fare_value) {						
				if($fare_value=="mins" || $fare_value=="min"){
					if($plus_time!=""){
						$plus_time .= "+".$fare_duration_time[($fare_key-1)]."minutes";
					} else{
						$plus_time .= "+".$fare_duration_time[($fare_key-1)]."minutes";
					}
				} else if($fare_value=="hour"){
					$plus_time .= '+'.$fare_duration_time[($fare_key-1)]."hours ";
				}
			}
			$new_time = date($format,strtotime($plus_time,strtotime($current_time)));
			return $new_time;
		} catch (Exception $e) {
			Exceptions::exception($e);	
		}
	}
}