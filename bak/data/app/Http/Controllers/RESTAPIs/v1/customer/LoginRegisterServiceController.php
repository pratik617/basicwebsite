<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Helper\ResponseMessage;
use App\Helper\RandomHelper;
use App\Helper\SMSHelper;
use App\Helper\Exceptions;
use App\Helper\MailHelper;
use App\Model\OAuth\OauthAccessToken;
use App\Model\Notificationlog;
use Response;
use Validator;
use Mail;
use Auth;
use Hash;
use URL;
use File;
use Lcobucci\JWT\Parser;

/**
   * Login Register Verify and Forgot for customer
   *
   *
   * @package    Laravel
   * @subpackage LoginRegisterServiceController
   * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
   */

class LoginRegisterServiceController extends Controller
{
	/**
       *
       * Login a customer
       *
       * @param string $country_code  country code
       * @param string $contact_no  contact number
       * @param string $password  password
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function login(Request $request)
    {
    	try {
    		$rules = [
    			'country_code' => 'required',
    			'contact_no' => 'required',
				'password' => 'required',
				'device_type' => 'required|in:Ios,Android',
				'device_token' => 'required',
			];
			$customeMessage = [
				'country_code.required' => 'Please enter country code.',
				'contact_no.required' => 'Please enter contact no.',
				'password.required' => 'Please enter password.',
				'device_type.required' => 'Please sent device type.',
				'device_type.in' => 'Please sent device type Ios and Android',
				'device_token.required' => 'Please sent device token.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$country_code = $request->country_code;
	        	$contact_no = $request->contact_no;
	        	$password = trim($request->password);
	        	$device_type = trim($request->device_type);
	        	$device_token = trim($request->device_token);

	        	if(User::where('country_code',$country_code)->where('contact_no',$contact_no)->exists()){
	        		$user = User::where('country_code',$country_code)->where('contact_no',$contact_no)->get()->first();
	        		if(User::where('country_code',$country_code)->where('contact_no',$contact_no)->where('email_verified_at','!=','')->exists()){
	        			if(Hash::check($password,$user->password)){
				        	if(Auth::attempt(['country_code'=>$country_code, 'contact_no' => $contact_no,'password' => $password])) {
				        		$user = Auth::user();
				        		$token =  $user->createToken('MyApp')->accessToken;
				        		Notificationlog::create('customer',$user->id,'Login in to your '.$device_type.' device.',"yes");
				        		$user_data = User::find($user->id);
				        		$user_data->device_type = $device_type;
				        		$user_data->device_token = $device_token;
				        		if($user_data->save()){
					        		$user_data = User::where('id',$user->id)->select('id','name','email','country_code','contact_no','profile','invite_code')->get()->first();

					        		$user_data['token'] = $token;
					        		return json_encode(['status' => 1, 'error' => 200, 'message' => 'Login success.', 'data' => $user_data]);
					        		exit;
				        		} else{
				        			ResponseMessage::error("Somethig went wrong.");
				        		}
				        	} else{
				        		ResponseMessage::error("invalid contact number and password.");
				        	}
			        	} else{
			        		ResponseMessage::error("Password not match.");
			        	}
		        	} else{
		        		$user = User::where('country_code',$country_code)->where('contact_no',$contact_no)->get()->first();
		        		$SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
		                $otp = $SMSresponse['otp'];
			        	MailHelper::sendOTP($user,$otp);
		        		$user_verify = User::find($user->id);
	    				$user_verify->otp = $otp;
                    	$user_verify->save();
                    	return json_encode(['status' => 2, 'message' => 'User not verified.', 'data' => ["country_code"=>$user_verify->country_code,"contact_no"=>$user_verify->contact_no] ]);
		        	}
	        	} else{
	        		ResponseMessage::error("User not exits.");
	        	}
	        }
		} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       *
       * Register a customer
       *
       * @param string $name  customer name
       * @param string $email  customer emial address
       * @param string $country_code  customer country code
       * @param string $mobile  customer mobile number
       * @param string $password  password
       * @param string $social_type  customer register type 0=basic, 1=facebook, 2=gmail, 3=linkedin
       * @param string $image  contact profile image
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function signup(Request $request)
    {
    	try {
    		$rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'country_code' => "required",
                'mobile' => 'required|numeric|digits_between:1,15|unique:users,contact_no',
                'password' => '',
                'social_type' => 'required',
                'social_id' => '',
                'image' => 'image'
            ];
            $customeMessage = [
                'name.required' => 'Please enter name.',
                'email.required' => 'Please enter email address.',
                'email.email' => 'Invalid email address.',
                'email.unique' => 'Email is already exists.',
                'country_code.required' => 'Please enter country code.',
                'mobile.required' => 'Please enter contact number',
                'mobile.digits_between' => 'Please enter only digits and maximum 15 digits.',
                'mobile.unique' => 'Contact number is already exists.',
                'password.required' => 'Please enter password.',
                'password.confirmed' => 'Password and confirm password not match.',
                'image.image' => 'Profile image type invalid.',
                'social_type.required' => 'Please send social type',
            ];
            if($request->social_type=="0"){
            	$rules['password'] = 'required';
            } else{
            	$rules['social_id'] = 'required';
            }
            $validator = Validator::make($request->all(),$rules,$customeMessage);

            if( $validator->fails() ){
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$invite_code = RandomHelper::inviteCode();
	        	$name = trim(strip_tags($request->name));
	        	$email = trim(strip_tags($request->email));
	        	$country_code = trim(strip_tags($request->country_code));
	        	$contact_no = trim(strip_tags($request->mobile));
	        	$social_type = trim(strip_tags($request->social_type));
	        	$social_id = trim(strip_tags($request->social_id));
	        	if($social_type=="0"){
	        		$password = Hash::make($request->password);
	        	} else{
	        		$password = "";
	        	}

	        	if($request->hasFile('image')) {
	        		$time = md5(time());
	        		$file = $request->image;
	        		$extension = $file->getClientOriginalExtension();
	        		$profile_image = $time.'.'.$file->getClientOriginalExtension();

	        		$file->move(public_path('user/'.$invite_code.'/profile/'),$profile_image);
	        		$profile = 'user/'.$invite_code.'/profile/'.$profile_image;

	        	} else{
	        		$profile = "";
	        	}

        		$SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
                $otp = $SMSresponse['otp'];
	        	MailHelper::sendOTP($request->all(),$otp);

	        	$user = new User;
	        	$user->name = $name;
	        	$user->email = $email;
	        	$user->country_code = $country_code;
	        	$user->contact_no = $contact_no;
	        	$user->password = $password;
	        	$user->profile =$profile;
	        	$user->invite_code = $invite_code;
	        	if($social_type=="1"){
	        		$user->facebook_id = $social_id;
	        	} elseif($social_type=="2"){
	        		$user->gmail_id = $social_id;
	        	} elseif($social_type=="3"){
	        		$user->linkedin_id = $social_id;
	        	}
	        	$user->otp = $otp;
	        	if($user->save()) {
	        		$user = User::where('id',$user->id)->select('id','name','email','country_code','contact_no','profile','invite_code')->get()->first();
	                ResponseMessage::success("Registration",$user);
	        	} else{
	        		ResponseMessage::error('Somethig was wrong please try again.');
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       *
       * verify a customer
       *
       * @param string $code  customer otp code
       * @param string $mobile  customer mobile number
       * @param string $country_code  customer country code
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function verify(Request $request)
    {
    	try {
    		$rules = [
                'code' => 'required',
                'mobile' => 'required',
                'country_code' => "required",
                'device_type' => 'required|in:Ios,Android',
				'device_token' => 'required',
            ];
            $customeMessage = [
            	'code.required' => 'Please enter otp.',
            	'mobile.required' => 'Please enter contact number.',
            	'country_code.required' => 'Please enter country code.',
            	'device_type.required' => 'Please sent device type.',
				'device_type.in' => 'Please sent device type Ios and Android',
				'device_token.required' => 'Please sent device token.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	    		$country_code = trim(strip_tags($request->country_code));
	    		$mobile = trim(strip_tags($request->mobile));
	    		$code = trim(strip_tags($request->code));
	    		$device_type = trim($request->device_type);
	        	$device_token = trim($request->device_token);

	    		if( User::where('country_code',$country_code)->where('contact_no',$mobile)->exists() ){
	    			$user = User::where('country_code',$country_code)->where('contact_no',$mobile)->get()->first();
	    			if( $code == $user->otp ){
	    				$user_verify = User::find($user->id);
	    				$user_verify->email_verified_at = date('Y-m-d H:i:s');
	    				$user_verify->device_type = $device_type;
				        $user_verify->device_token = $device_token;
	    				$user_verify->otp = "";
                    	$user_verify->save();

                    	$user = Auth::loginUsingId($user_verify->id);
                    	$token =  $user_verify->createToken('MyApp')->accessToken;

		        		$user_data = User::where('id',$user->id)->select('id','name','email','country_code','contact_no','profile','invite_code')->get()->first();
		        		$user_data['token'] = $token;

	    				ResponseMessage::success("User verified.",$user_data);
	    			} else{
	    				ResponseMessage::error('Wrong OTP.');
	    			}
	    		} else{
	    			ResponseMessage::error('User not found.');
	    		}
    		}
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       *
       * Login customer with social login
       *
       * @param string $email  customer email
       * @param string $appid  customer social app is
       * @param string $social_type  customer login social type like facebook,gmail and linkedin
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function login_with_social(Request $request)
    {
    	try {
    		$rules = [
                'email' => 'required',
                'appid' => 'required',
                'social_type' => "required",
                'device_type' => 'required|in:Ios,Android',
				'device_token' => 'required',
            ];
            $customeMessage = [
            	'email.required' => 'Please enter email.',
            	'appid.required' => 'Please send social app id.',
            	'social_type.required' => 'Please enter social type.',
            	'device_type.required' => 'Please sent device type.',
				'device_type.in' => 'Please sent device type Ios and Android',
				'device_token.required' => 'Please sent device token.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	    		$email = trim(strip_tags($request->email));
	    		$appid = trim(strip_tags($request->appid));
	    		$social_type = trim(strip_tags($request->social_type));
	    		$device_type = trim(strip_tags($request->device_type));
	        	$device_token = trim(strip_tags($request->device_token));

	    		if(User::where('email',$email)->exists()){
		    		if($social_type == "1"){
			    			$user = User::where('email',$email)->where('facebook_id',$appid)->get()->first();
			    			if($user){
			    				if($user->email_verified_at!=""){
					    			$user = Auth::loginUsingId($user->id);
					    			$user_verify = User::find($user->id);
					    			$user_verify->device_type = $device_type;
						        	$user_verify->device_token = $device_token;
						        	$user_verify->save();
					        		$token =  $user->createToken('MyApp')->accessToken;
					        		$user_data = User::where('id',$user->id)->select('id','name','email','country_code','contact_no','profile','invite_code')->get()->first();
					        		$user_data['token'] = $token;
					        		return json_encode(['status' => 1, 'error' => 200, 'message' => 'Login success.', 'data' => $user_data]);
					        		exit;
			    				} else{
					        		$SMSresponse = SMSHelper::sendOTP($user->country_code,$user->contact_no);
					                $otp = $SMSresponse['otp'];
						        	MailHelper::sendOTP($user,$otp);

					        		$user_verify = User::find($user->id);
				    				$user_verify->otp = $otp;
			                    	$user_verify->save();

			                    	$response = User::select('country_code','contact_no')->where('email',$email)->where('facebook_id',$appid)->get()->first();

			    					return json_encode(['status' => 2, 'message' => "User not verified" , 'data' =>$response]);
			    					exit;
			    				}
			    			} else{
			    				return json_encode(['status' => 3, 'message' => "User not found" , 'data' => array()]);
			    				exit;
			    			}
		    		} elseif($social_type == "2"){
		    			$user = User::where('email',$email)->where('gmail_id',$appid)->get()->first();
		    			if($user){
		    				if($user->email_verified_at!=""){
				    			$user = Auth::loginUsingId($user->id);
				    			$user_verify = User::find($user->id);
				    			$user_verify->device_type = $device_type;
					        	$user_verify->device_token = $device_token;
					        	$user_verify->save();
				        		$token =  $user->createToken('MyApp')->accessToken;
				        		$user_data = User::where('id',$user->id)->select('id','name','email','country_code','contact_no','profile','invite_code')->get()->first();
				        		$user_data['token'] = $token;
				        		return json_encode(['status' => 1, 'error' => 200, 'message' => 'Login success.', 'data' => $user_data]);
				        		exit;
			        		} else{
				        		$SMSresponse = SMSHelper::sendOTP($user->country_code,$user->contact_no);
				                $otp = $SMSresponse['otp'];
					        	MailHelper::sendOTP($user,$otp);

				        		$user_verify = User::find($user->id);
			    				$user_verify->otp = $otp;
		                    	$user_verify->save();

		                    	$response = User::select('country_code','contact_no')->where('email',$email)->where('facebook_id',$appid)->get()->first();

			        			return json_encode(['status' => 2, 'message' => "User not verified" , 'data' => $response ]);
			        			exit;
			        		}
		    			} else{
		    				return json_encode(['status' => 3, 'message' => "User not found" , 'data' => array()]);
		    				exit;
		    			}
		    		} elseif($social_type == "3"){
		    			$user = User::where('email',$email)->where('linkedin_id',$appid)->get()->first();
		    			if($user){
		    				if($user->email_verified_at!=""){
				    			$user = Auth::loginUsingId($user->id);
				    			$user_verify = User::find($user->id);
				    			$user_verify->device_type = $device_type;
					        	$user_verify->device_token = $device_token;
					        	$user_verify->save();
				        		$token =  $user->createToken('MyApp')->accessToken;
				        		$user_data = User::where('id',$user->id)->select('id','name','email','country_code','contact_no','profile','invite_code')->get()->first();
				        		$user_data['token'] = $token;
				        		return json_encode(['status' => 1, 'error' => 200, 'message' => 'Login success.', 'data' => $user_data]);
				        		exit;
			        		} else{
				        		$SMSresponse = SMSHelper::sendOTP($user->country_code,$user->contact_no);
				                $otp = $SMSresponse['otp'];
					        	MailHelper::sendOTP($user,$otp);

				        		$user_verify = User::find($user->id);
			    				$user_verify->otp = $otp;
		                    	$user_verify->save();

		                    	$response = User::select('country_code','contact_no')->where('email',$email)->where('facebook_id',$appid)->get()->first();

			        			return json_encode(['status' => 2, 'message' => "User not verified" , 'data' => $response ]);
			        			exit;
			        		}
		    			} else{
		    				return json_encode(['status' => 3, 'message' => "User not found" , 'data' => array()]);
		    				exit;
		    			}
		    		} else{
		    			ResponseMessage::error('Social type cant match.');
		    			exit;
		    		}
	    		} else{
	    			return json_encode(['status' => 3, 'message' => "User not found" , 'data' => array()]);
	    			exit;
	    		}
	    	}
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function forgot(Request $request)
    {
    	try {
    		$rules = [
                'email' => 'required',
            ];
            $customeMessage = [
            	'email.required' => 'Please enter email.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$email = trim(strip_tags($request->email));
	        	if(User::where('email',$email)->exists()){
	        		$user = User::select('id','name','email')->where('email',$email)->get()->first();
	        		$token = RandomHelper::forgotToken();
	        		if($token !=""){
	        			$userUpdate = User::find($user->id);
	        			$userUpdate->password_reset = $token;
	        			$userUpdate->save();
	        			MailHelper::forgotCustomer($user,$email,$token);
	        			ResponseMessage::success("Verification mail send successfully.");
	        		}
	        	} else{
	        		ResponseMessage::error("Email address not exists.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function logout(Request $request)
    {
    	try {
    		$rules = [
          'customer_id' => 'required',
        ];
        $customeMessage = [
          'customer_id.required' => 'Please enter customer id.',
        ];
        $validator = Validator::make($request->all(),$rules,$customeMessage);

        if( $validator->fails() ){
          $errors = $validator->errors();
          ResponseMessage::error($errors->first());
        } else {
	    		$customer_id = strip_tags(trim($request->customer_id));
	    		if(User::where('id',$customer_id)->exists()) {
            if(Auth::guard('user_api')->check()) {
	    				$customer = User::find($customer_id);
	    				$customer->device_type = null;
	    				$customer->device_token = null;
	    				if($customer->save()) {
			                $value = $request->bearerToken();
			                $id = (new Parser())->parse($value)->getHeader('jti');
			                $token = $customer->tokens->find($id);
			                $token->revoke();
					    	//Auth::guard('user_api')->user()->AauthAcessToken()->delete();
							ResponseMessage::success('User successfully logout.');
	    				} else {
	    					ResponseMessage::error('Somethig went wrong.');
	    				}
				    } else {
				    	$customer = User::find($customer_id);
	    				$customer->device_type = null;
	    				$customer->device_token = null;
	    				if($customer->save()) {
							ResponseMessage::success('User successfully logout.');
	    				} else{
	    					ResponseMessage::error('Somethig went wrong.');
	    				}
				    }
	    		} else{
	    			ResponseMessage::error('User not found');
	    		}
	    	}
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
    public function getImage($folder,$name)
    {
    	try {
    		$file = File::get(public_path()."/user/".$folder."/profile/".$name);
			$response = Response::make($file, 200);
			$response->header('Content-Type', 'application/image');
			return $response;
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
