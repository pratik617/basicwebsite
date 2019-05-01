<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exceptions;

class ReportsController extends Controller
{
    public function index()
    {
    	try {
    		return view('admin.reports.reports');
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }
}
