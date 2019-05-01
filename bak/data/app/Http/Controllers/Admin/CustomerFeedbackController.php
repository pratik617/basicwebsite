<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exceptions;

class CustomerFeedbackController extends Controller
{
    public function index()
    {
    	try {
    		return view('admin.customer_feedback.customer_feedback');
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }
}
