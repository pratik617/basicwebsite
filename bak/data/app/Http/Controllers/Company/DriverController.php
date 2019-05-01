<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exceptions;
use App\Model\Driver\Driver;

class DriverController extends Controller
{
    public function index()
    {
    	try {
    		$data['driver']=Driver::orderby('id','DESC')->select('id','name','email','country_code','contact_code','profile')->get();
    		return view('admin.individual_driver.individual_driver')->with($data);
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }
  
}
