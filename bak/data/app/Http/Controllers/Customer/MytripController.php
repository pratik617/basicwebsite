<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MytripController extends Controller
{
    public function index()
    {
    	try {
    		return view('customer.mytrip.mytrip');
    	} catch (Exception $e) {
    		
    	}
    }
}
