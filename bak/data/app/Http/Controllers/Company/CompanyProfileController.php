<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exceptions;

class CompanyProfileController extends Controller
{
    public function index()
    {
    	try {
    		return view('company.company_profile.company_profile');
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }
}
