<?php

namespace App\Http\Controllers\RESTAPIs\v1\country;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\ResponseMessage;
use App\Helper\Exceptions;
use App\Model\Country;
use DB;

class CountryServiceController extends Controller
{
    public function get_country($code="")
    {
    	try {
            if($code!=""){
                if(Country::where('code_2',$code)->exists()){
                    $countrys = DB::select(DB::raw("select `name`,`phone_code`,`code_2` from `countrys` WHERE `status`='active' ORDER BY FIELD(`code_2`,'$code') DESC, `phone_code`"));
                } else{
                    $countrys = Country::select('name','phone_code','code_2')
                                    ->where('status','=','active')
                                    ->orderBy('phone_code','ASC')
                                    ->get();
                }
            } else{
        		$countrys =  Country::select('name','phone_code','code_2')
        						->where('status','=','active')
        						->orderBy('phone_code','ASC')
        						->get();
            }
    		ResponseMessage::success("Country list",$countrys);
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
