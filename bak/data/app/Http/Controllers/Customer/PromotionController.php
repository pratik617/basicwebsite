<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function index()
    {
    	try {
    		return view('customer.promotion.promotion');
    	} catch (Exception $e) {
    		
    	}
    }
}
