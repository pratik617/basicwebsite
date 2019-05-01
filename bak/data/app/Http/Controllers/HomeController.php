<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\SMSHelper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $SMSresponse = SMSHelper::sendSMS("+91","8347012816");
        // dd($SMSresponse['otp']);
        return view('home');
    }
}
