<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Country;
use App\Helper\SMSHelper;
use Mail;
use Auth;
use Hash;

class ProfileController extends Controller
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

  public function index()
  {
  	try {
      //dd(Auth()->user());
      $countries = Country::select('phone_code')->orderBy('phone_code','ASC')->get();
      $user = array(
        'name' => strtolower(Auth()->user()->name),
        'email' => Auth()->user()->email,
        'country_code' => Auth()->user()->country_code,
        'contact_no' => Auth()->user()->contact_no,
        'invite_code' => Auth()->user()->invite_code
      );
      //dd($user);
  		return view('customer.profile.profile')->with(['user' => $user, 'countries' => $countries]);
  	} catch (Exception $e) {

  	}
  }

  public function store(Request $request) {
    try {
      $this->validate($request, [
          'name' => 'required',
          'email' => 'email',
          'invite_code' => 'required',
          'country_code' => 'required',
          'contact_no' => 'required'
      ]);


      $user = User::find(Auth()->user()->id);
      $user->name = trim(strip_tags($request->input('name')));

      if ($request->input('change_password')) {
        echo $request->input('current_password');
        echo '<br>';
        echo $request->input('new_password');
        echo '<br>';
        echo $request->input('new_password_confirm');
        echo '<br>';
        //exit;

        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!(Hash::check($request->input('current_password'), Auth::user()->password))) {
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->input('current_password'), $request->input('new_password')) == 0){
          return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $user->password = bcrypt($request->input('new_password'));
      }

      if ($user->email != $request->input('email') || $user->country_code != $request->input('country_code') || $user->contact_no != $request->input('contact_no')) {
        $SMSresponse = SMSHelper::sendOTP($request->input('country_code'), $request->input('contact_no'));
        $otp = $SMSresponse['otp'];
        session(['register_otp' => $otp]);
        Mail::send('mail.register.send_otp',['data'=>$request->all(),'otp'=>$otp] , function($message) use ($request) {
            $message->to($request->email, $request->name)->subject('RideApp Verification Code');
            $message->from('rideapp@gmail.com','RideApp');
        });

        $user->email = trim(strip_tags($request->input('email')));
        $user->country_code = trim(strip_tags($request->input('country_code')));
        $user->contact_no = trim(strip_tags($request->input('contact_no')));
        $user->email_verified_at = null;
        if ($user->save()) {
          return view('customer.profile.verify-otp');
        } else {
        }
        return back()->with('error','Something went wrong, please try again!');

      } else {
        $user->email = trim(strip_tags($request->input('email')));
        $user->country_code = trim(strip_tags($request->input('country_code')));
        $user->contact_no = trim(strip_tags($request->input('contact_no')));

        if ($user->save()) {
          return redirect('/dashboard')->with('success', 'Profile Updated!');
        } else {
          return back()->with('error','Something went wrong, please try again!');
        }
      }

    } catch (Exception $e) {
      Exceptions::exception($e);
    }
  }

  public function verifyOtp(Request $request) {
    try {
      $this->validate($request, [
          'otp' => 'required',
      ]);

      $otp = $request->input('otp');
      $session_otp = session('register_otp');
      if ($otp == $session_otp) {
        $user = User::find(Auth::user()->id);
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->save();
        session()->forget('register_otp');
        return redirect('/dashboard')->with('success', 'Profile Updated!');
      } else {
        return back()->with('error','Invalid OTP, please try again');
      }
    } catch (Exception $e) {
      Exceptions::exception($e);
    }

  }
}
