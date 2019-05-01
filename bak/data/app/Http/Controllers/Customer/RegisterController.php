<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Helper\RandomHelper;
use App\Model\Exceptions;
use App\Model\Driver\Driver;
use Mail;
use Validator;
use Session;
use Hash;
use App\User;
use App\Helper\SMSHelper;
use App\Model\Country;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
    public function index()
    {
    	try {
            $data['country'] = Country::select('name','phone_code','code_2')->orderBy('phone_code','ASC')->get();
    		return view('customer.register.register')->with($data);
    	} catch (Exception $e) {
        Exceptions::exception($e);
    	}
    }

    public function register(Request $request)
    {
    	try {
            // validation
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'contact_no' => 'required|numeric|digits_between:1,15|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ];
            $customeMessage = [
                'name.required' => 'Please enter name.',
                'email.required' => 'Please enter email address.',
                'email.email' => 'Invalid email address.',
                'email.unique' => 'Email is already exists.',
                'contact_no.required' => 'Please enter contact number',
                'contact_no.digits_between' => 'Please enter only digits and maximum 15 digits.',
                'contact_no.unique' => 'Contact number is already exists.',
                'password.required' => 'Please enter password.',
                'password.confirmed' => 'Password and confirm password not match.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);

            if( $validator->fails() ){
                return back()->withInput()->withErrors($validator->errors());
            } else{
                $client = new Client();
                /*
                $response = $client->request('POST', 'http://54.186.22.208/api/v1/signup_cust', [
                    'form_params' => [
                        'name' => trim(strip_tags($request->name)),
                        'email' => trim(strip_tags($request->email)),
                        'country_code' => trim(strip_tags($request->country_code)),
                        'mobile' => trim(strip_tags($request->contact_no)),
                        'password' => $request->password,
                        'social_type' => 0,
                        'social_id' => ''
                    ]
                ]);

                $response_body = $response->getBody();
                //dd($response_body);

                $response_data = json_decode($response_body);

                if ($response_data->status == 1) {
                  return redirect()->intended(route('customer.login'))->with('success', 'You are registered successfully! please login');
                } else {
                  return redirect()->back()->with('error','Sorry, Something went wrong. Please try again.');
                }
                */

                $name = trim(strip_tags($request->name));
                $email = trim(strip_tags($request->email));
                $country_code = trim(strip_tags($request->country_code));
                $contact_no = $request->contact_no;
                $password = Hash::make($request->password);
                $invite_code = 'jikkk452';

                $user = New User;
                $user->name = $name;
                $user->email = $email;
                $user->country_code = $country_code;
                $user->contact_no = $contact_no;
                $user->password = $password;
                $user->invite_code = $invite_code;
                if ($request->s_type) {
                  if ($request->s_type == 'google')
                    $user->gmail_id = $request->s_id;
                  else if ($request->s_type == 'facebook')
                    $user->facebook_id = $request->s_id;
                  else if ($request->s_type == 'linkedin')
                    $user->linkedin_id = $request->s_id;
                }

                if($user->save()) {
                    $SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
                    $otp = $SMSresponse['otp'];
                    session(['register_otp' => $otp]);
                    session(['user_id' => $user->id]);

                    Mail::send('mail.register.send_otp',['data'=>$request->all(),'otp'=>$otp] , function($message) use ($request) {
                        $message->to($request->email, $request->name)->subject('RideApp Verification Code');
                        $message->from('rideapp@gmail.com','RideApp');
                    });

                    return view('customer.register.get_otp');
                } else{
                  return 'fail';
                    return back()->with('error','Registration fail please try again');
                }
            }
    	} catch (Exception $e) {
        Exceptions::exception($e);
    	}
    }

    public function otpVerification(Request $request)
    {
        try {
            $rules = ['verification_otp' => 'required'];
            $customeMessage = ['verification_otp.required' => 'Please enter otp.'];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
                return back()->withInput()->withErrors($validator->errors());
            } else{
                $verification_otp = trim(strip_tags($request->verification_otp));
                $session_otp = session('register_otp');
                if( $verification_otp == $session_otp ){
                    $user_id = session('user_id');
                    Auth::loginUsingId($user_id);
                    $user = User::find(Auth::user()->id);
                    $user->email_verified_at = date('Y-m-d H:i:s');
                    $user->save();
                    session()->forget('register_otp');
                    session()->forget('user_id');
                    return redirect()->route('customer.dashboard');
                } else{
                    return back()->with('error','invalid OTP please try again');
                }
            }
        } catch (Exception $e) {
          Exceptions::exception($e);
        }
    }

    public function showLoginForm()
    {
    	try {
    		return view('customer.login.login');
    	} catch (Exception $e) {
        Exceptions::exception($e);
    	}
    }

    public function login(Request $request)
    {
    	try {
        // validation
        $rules = [
          'email' => 'required|email',
          'password' => 'required'
        ];
        $customeMessage = [
            'email.required' => 'Please enter email address.',
            'email.email' => 'Invalid email address.',
            'password.required' => 'Please enter password.',
        ];
        $validator = Validator::make($request->all(),$rules,$customeMessage);
        //dd($request->all());
        if( $validator->fails() ) {
            return back()->withInput()->withErrors($validator->errors());
        } else {
          $user = User::where('email', $request->email)->get()->first();
          if ($user) {
            $client = new Client();
            //dd('ghehe');

            $response = $client->request('POST', 'http://54.186.22.208/api/v1/login_cust', [
                'form_params' => [
                    'contact_no' => $user->contact_no,
                    'password' => $request->password,
                    'country_code' => $user->country_code,
                    'device_type' => 'Android',
                    'device_token' => '1234'
                ]
            ]);
            /*
            $response = $client->request('POST', 'http://54.186.22.208/api/v1/login_cust', [
                'form_params' => [
                    'contact_no' => $request->input('contact_no'),
                    'password' => $request->input('password'),
                    'country_code' => $request->input('country_code'),
                    'device_type' => 'Android',
                    'device_token' => '1234'
                ]
            ]);*/

            $response_body = $response->getBody();

            $response_data = json_decode($response_body);
            //dd($response_data);
            if ($response_data->status == 1 && $response_data->error == 200) {
              session(['token' => $response_data->data->token]);
              Auth::attempt(['email'=>$request->email,'password'=>$request->password,'status'=>'active'],$request->remember);
              //dd('heheh');
              return redirect()->intended(route('customer.dashboard'));
            } else {
              return redirect()->back()->with('error','Email and password not match.');
            }
          } else {
            return redirect()->back()->with('error','User does not exists.');
          }
        }
    	} catch (Exception $e) {
        Exceptions::exception($e);
    	}
    }

    public function logout(Request $request)
    {
    	try {
        Auth::user()->AauthAcessToken()->delete();
        Auth::logout();

	        return redirect('/');
    	} catch (Exception $e) {
        Exceptions::exception($e);
    	}
    }

    public function forgot($token)
    {
        try {
            $token = trim(strip_tags($token));
            if(User::where('password_reset',$token)->exists()){
                $data['token'] = $token;
            } else{
                $data['token'] = "";
            }
            return view('customer.forgot.forgot')->with($data);
        } catch (Exception $e) {
          Exceptions::exception($e);
        }
    }
    public function forgotDriver($token)
    {
        try {
            $token = trim(strip_tags($token));
            if(Driver::where('password_reset',$token)->exists()){
                $data['token'] = $token;
            } else{
                $data['token'] = "";
            }
            return view('driver.forgot.forgot')->with($data);
        } catch (Exception $e) {

        }
    }

    public function forgotResetDriver(Request $request)
    {
        try {
            $rules = [
                'password' => 'required|min:6',
                'cpassword' => 'required|same:password'
            ];
            $customeMessage = [
                'password.required' => 'Please enter password.',
                'password.min' => 'Please enter minimum 6 character.',
                'cpassword.required' => 'Please enter confirm password.',
                'password.min' => 'Please enter minimum 6 character.',
                'cpassword.same' => 'Password and confirm password not match.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
                return back()->withInput()->withErrors($validator->errors());
            } else{
                $token = trim(strip_tags($request->token));
                $password = trim(strip_tags($request->password));
                $cpassword = trim(strip_tags($request->cpassword));
                if($password===$cpassword){
                    if(Driver::where('password_reset',$token)->exists()){
                        $user = Driver::where('password_reset',$token)->get()->first();
                        $userUpdate = Driver::find($user->id);
                        $userUpdate->password_reset = "";
                        $userUpdate->password = Hash::make($password);
                        $userUpdate->updated_at = date('Y-m-d H:i:s');
                        if($userUpdate->save()){
                            return view('customer.forgot.success');
                        } else{
                        }
                    } else{
                        return back()->with('error','Something went wrong.');
                    }
                } else{
                    return back()->with('error','Password and confirm password not match.');
                }
            }
        } catch (Exception $e) {

        }
    }

    public function forgotReset(Request $request)
    {
        try {
            $rules = [
                'password' => 'required|min:6',
                'cpassword' => 'required|same:password'
            ];
            $customeMessage = [
                'password.required' => 'Please enter password.',
                'password.min' => 'Please enter minimum 6 character.',
                'cpassword.required' => 'Please enter confirm password.',
                'password.min' => 'Please enter minimum 6 character.',
                'cpassword.same' => 'Password and confirm password not match.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
                return back()->withInput()->withErrors($validator->errors());
            } else{
                $token = trim(strip_tags($request->token));
                $password = trim(strip_tags($request->password));
                $cpassword = trim(strip_tags($request->cpassword));
                if($password===$cpassword){
                    if(User::where('password_reset',$token)->exists()){
                        $user = User::where('password_reset',$token)->get()->first();
                        $userUpdate = User::find($user->id);
                        $userUpdate->password_reset = "";
                        $userUpdate->password = Hash::make($password);
                        $userUpdate->updated_at = date('Y-m-d H:i:s');
                        if($userUpdate->save()){
                            return view('customer.forgot.success');
                        } else{
                        }
                    } else{
                        return back()->with('error','Something went wrong.');
                    }
                } else{
                    return back()->with('error','Password and confirm password not match.');
                }
            }
        } catch (Exception $e) {

        }
    }
}
