<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Customer\Tripdetail;
use App\Model\Exceptions;

class TripDetailsController extends Controller
{
    public function index()
    {
    	try {
    		$data['trips'] = Tripdetail::orderby('created_at','ASC')->paginate(10);
    		return view('admin.trip_details.trip_details')->with($data);
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }
}
