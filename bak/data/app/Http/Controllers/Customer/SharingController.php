<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SharingController extends Controller
{
    public function index()
    {
    	try {
    		return view('customer.share.share');
    	} catch (Exception $e) {
    		
    	}
    }
}
