<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exceptions;
use Validator;
use Auth;

class CouponCodeController extends Controller
{
    public function index()
    {
     try{
         return view('admin.coupon_code.coupon_code');    	
    	}catch(\Exception $e){
    	 $Exceptions = New Exceptions;
    	 $Exceptions->sendException($e);	
    	}
    }
}
