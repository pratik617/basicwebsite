<?php

namespace App\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Hash;

class RandomHelper extends Controller
{
	public static function randomKey($length) 
	{
	    $pool = array_merge(range(0,9),range('A', 'Z'));
	    $key = "";
	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}
	public static function randomOTP($length = 6) 
	{
	    $pool = array_merge(range(0,9));
	    $key = "";
	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}
	public static function inviteCode($length = 10) 
	{
	    $pool = array_merge(range(0,9),range('A', 'Z'));
	    $key = "";
	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }

	    $validator = Validator::make(['invite_code'=>$key],['invite_code'=>'unique:users,invite_code']);
	    if($validator->fails()){
	        return $this->inviteCode($length);
	    } else{
	    	return $key;
	    }
	}
	public static function driverPassword($length = 6) 
	{
	    $pool = array_merge(range(0,9),range('A', 'Z'));
	    $key = "";
	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}
	public static function forgotToken($length = 64)
	{
		$pool = array_merge(range(0,9),range('A', 'Z'),range('a', 'z'));
	    $key = "";
	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}
	public static function tripId($length = 64){
 		$pool = array_merge(range(0,9),range('a', 'z'));
	    $key = "";
	    for($i=0; $i < $length; $i++) {
	    	$key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}
}