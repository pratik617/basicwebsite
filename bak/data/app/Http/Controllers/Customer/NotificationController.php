<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
    	try {
    		return view('customer.notification.notification');
    	} catch (Exception $e) {
    		
    	}
    }
}
