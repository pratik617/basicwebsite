<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MywalletController extends Controller
{
    public function index()
    {
    	try {
    		return view('customer.mywallet.mywallet');
    	} catch (Exception $e) {
    		
    	}
    }
}
