<?php

namespace App\Http\Controllers\RESTAPIs\v1\driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Driver\Driver;
use App\Helper\ResponseMessage;
use App\Helper\RandomHelper;
use App\Helper\SMSHelper;
use App\Helper\MailHelper;
use App\Model\Vehicletype;
use App\Model\Vehiclecategory;
use App\Model\Company\Company;
use App\Model\Notificationlog;
use App\Helper\Exceptions;
use Response;
use Validator;
use Mail;
use Auth;
use Hash;
use URL;
use File;

/**
   * Login Register Verify and Forgot for driver
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
                'email' => 'required|string|email|max:255|unique:drivers',
                'country_code' => "required",
                'mobile' => 'required|numeric|digits_between:1,15|unique:drivers,contact_no',
                'password' => '',
                'profile' => 'image',
                'address' => '',
                'company_id' => '',
                'vehicle_number' => 'required',
                'vehicle_type' => 'required',
                'vehicle_category' => 'required',
                'licence_number' => 'required',
                'document' => 'required',
                'bank_account_number' => 'required',
                'bank_name' => 'required',
                'account_holder_name' => 'required',
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
                'profile.image' => 'Profile image type invalid.',
                'vehicle_number.required' => 'Please enter vehicle number.',
                'vehicle_type.required' => 'Please select vehicle type.',
                'vehicle_category.required' => 'Please select vehicle category.',
                'licence_number.required' => 'Please enter licence number.',
                'document.required' => 'Please select document.',
                'bank_account_number.required' => 'Please enter bank account number.',
                'bank_name.required' => 'Please enter bank name.',
                'account_holder_name.required' => 'Please enter bank account holder name.',
            ];

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
				$address = trim(strip_tags($request->address));
				$company_id = trim(strip_tags($request->company_id));
				$vehicle_number = trim(strip_tags($request->vehicle_number));
				$vehicle_type = trim(strip_tags($request->vehicle_type));
				$vehicle_category = trim(strip_tags($request->vehicle_category));
				$licence_number = trim(strip_tags($request->licence_number));
				$bank_account_number = trim(strip_tags($request->bank_account_number));
				$bank_name = trim(strip_tags($request->bank_name));
				$account_holder_name = trim(strip_tags($request->account_holder_name));
				$status = "pending";

	        	if($request->hasFile('profile')) {
	        		$time = md5(time());
	        		$file = $request->profile;
	        		$extension = $file->getClientOriginalExtension();
	        		$profile_image = $time.'.'.$file->getClientOriginalExtension();

	        		$file->move(public_path('driver/'.$invite_code.'/profile/'),$profile_image);
	        		$profile = 'driver/'.$invite_code.'/profile/'.$profile_image;

	        	} else{
	        		$profile = "";
	        	}

	        	if($request->hasFile('document')) {
	        		$time = md5(time());
	        		$file = $request->document;
	        		$extension = $file->getClientOriginalExtension();
	        		$document_file = $time.'.'.$file->getClientOriginalExtension();

	        		$file->move(public_path('driver/'.$invite_code.'/document/'),$document_file);
	        		$document = 'driver/'.$invite_code.'/document/'.$document_file;

	        	} else{
	        		$document = "";
	        	}

	        	$SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
                $otp = $SMSresponse['otp'];
	        	MailHelper::sendOTP($request->all(),$otp);

	        	$driver = New Driver;
	        	$driver->otp = $otp;
				$driver->invite_code = $invite_code; 
				$driver->name = $name; 
				$driver->email = $email; 
				$driver->country_code = $country_code; 
				$driver->contact_no = $contact_no; 
				$driver->password = ""; 
				$driver->address = $address; 
				if($company_id!=""){
					$driver->company_id = $company_id; 
				}
				$driver->vehicle_number = $vehicle_number; 
				$driver->vehicle_type = $vehicle_type; 
				$driver->vehicle_category = $vehicle_category; 
				$driver->licence_number = $licence_number; 
				$driver->bank_account_number = $bank_account_number; 
				$driver->bank_name = $bank_name; 
				$driver->account_holder_name = $account_holder_name; 
				$driver->profile = $profile;
				$driver->document = $document;
				$driver->status = $status;
				if($driver->save()){ 
	                // $otp = $SMSresponse['otp'];
		        	// MailHelper::sendOTP($request->all(),$otp);
					// SMSHelper::driverRegister($request->all(),$password);
		        	// MailHelper::driverRegister($request->all(),$password);
					ResponseMessage::success("Driver successfully register.");
				} else{
					ResponseMessage::error("Something was wrong please try again.");
				}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
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
	    		$device_type = trim(strip_tags($request->device_type));
	        	$device_token = trim(strip_tags($request->device_token));

	    		if( Driver::where('country_code',$country_code)->where('contact_no',$mobile)->exists() ){
	    			$driver = Driver::where('country_code',$country_code)->where('contact_no',$mobile)->get()->first();
	    			if( $code == $driver->otp ){
		        		$password = RandomHelper::driverPassword();

	    				$driver_verify = Driver::find($driver->id);
	    				$driver_verify->email_verified_at = date('Y-m-d H:i:s');
	    				$driver_verify->otp = "";
	    				$driver_verify->device_type = $device_type;
	    				$driver_verify->device_token = $device_token;
	    				$driver_verify->password = Hash::make($password);
                    	$driver_verify->save();

                    	$driver = Auth::guard('driver')->loginUsingId($driver_verify->id);
                    	$token =  $driver_verify->createToken('MyApp')->accessToken;

		        		$driver_data = Driver::where('id',$driver->id)->select('id','name','email','country_code','contact_no','profile','invite_code','vehicle_type','vehicle_category')->get()->first();
		        		$driver_data['token'] = $token;

		        		SMSHelper::driverRegister($driver_data,$password);
		        		MailHelper::driverRegister($driver_data,$password);

	    				ResponseMessage::success("Driver verified.",$driver_data);
	    			} else{
	    				ResponseMessage::error('Wrong OTP.');	
	    			}
	    		} else{
	    			ResponseMessage::error('Driver not found.');
	    		}
    		}
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
    public function vehicle_type()
    {
    	try {
    		$vehicle_type =  Vehicletype::select('vehicle_types.id','vehicle_types.name')
    						->where('status','=','active')
    						->get();
    		ResponseMessage::success("Category type list",$vehicle_type);
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
    public function vehicle_category($id=0)
    {
    	try {
            if( $id=="" ){
            	ResponseMessage::error("Category not exists");
	        } else{
	    		$id = trim(strip_tags($id));
	    		if(Vehicletype::where('id',$id)->where('status','active')->exists()){
		    		$vehicle_category =  Vehiclecategory::select('id','name')
		    							->where('vehicle_type_id',$id)
		    							->where('status','=','active')
		    							->get();
		    		ResponseMessage::success("Category list",$vehicle_category);
	    		} else{
					ResponseMessage::error("Category not exists");
	    		}
    		}
    	} catch (Exception $e) {
    		Exceptions::exception($e);	
    	}
    }
    public function company_list()
    {
    	try {
    		$company_list = Company::select('name','id')->where('status','active')->get();
    		ResponseMessage::success("Company list.",$company_list);
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
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
	        	$password = $request->password;
	        	$device_type = trim($request->device_type);
	        	$device_token = trim($request->device_token);

	        	if(Driver::where('country_code',$country_code)->where('contact_no',$contact_no)->exists()){
	        		$driver = Driver::where('country_code',$country_code)->where('contact_no',$contact_no)->get()->first();
	        		if(Driver::where('country_code',$country_code)->where('contact_no',$contact_no)->where('email_verified_at','!=','')->exists()){
        				if(Hash::check($password,$driver->password)){
				        	if(Auth::guard('driver')->attempt(['country_code'=>$country_code, 'contact_no' => $contact_no,'password' => $password])) {
				        		$driver = Auth::guard('driver')->user();
				        		$token =  $driver->createToken('MyApp')->accessToken;
								Notificationlog::create('driver',$driver->id,'Login in to your '.$device_type.' device.',"yes");
				        		$driver_update = Driver::find($driver->id);
				        		$driver_update->device_type = $device_type;
				        		$driver_update->device_token = $device_token;
								$driver_update->save();			
				        		$driver_data = Driver::where('id',$driver->id)->select('id','name','email','country_code','contact_no','profile','invite_code','vehicle_type','vehicle_category')->get()->first();
				        		$driver_data['token'] = $token;
				        		return json_encode(['status' => 1, 'error' => 200, 'message' => 'Login success.', 'data' => $driver_data]);
				        		exit;
				        	} else{
				        		ResponseMessage::error("invalid contact number and password.");
				        	}
			        	} else{
			        		ResponseMessage::error("Password not match.");		
			        	}
		        	} else{
		        		$driver = Driver::where('country_code',$country_code)->where('contact_no',$contact_no)->get()->first();

		        		$SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
		                $otp = $SMSresponse['otp'];
			        	MailHelper::sendOTP($driver,$otp);

		        		$driver_verify = Driver::find($driver->id);
	    				$driver_verify->otp = $otp;
                    	$driver_verify->save();

                    	return json_encode(['status' => 2, 'message' => 'Driver not verified.', 'data' => ['country_code'=>$driver_verify->country_code,'contact_no'=>$driver_verify->contact_no] ]);
		        	}
	        	} else{
	        		ResponseMessage::error("Driver not exits.");
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
	        	if(Driver::where('email',$email)->exists()){
	        		$user = Driver::select('id','name','email')->where('email',$email)->get()->first();
	        		$token = RandomHelper::forgotToken();
	        		if($token !=""){
	        			$userUpdate = Driver::find($user->id);
	        			$userUpdate->password_reset = $token;
	        			$userUpdate->save();
	        			MailHelper::forgotDriver($user,$email,$token);
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
    public function getImage($folder,$name)
    {
    	try {    		
    		$file = File::get(public_path()."/driver/".$folder."/profile/".$name);
			$response = Response::make($file, 200);
			$response->header('Content-Type', 'application/image');
			return $response;
    	} catch (Exception $e) {
    		Exceptions::exception($e);		
    	}
    }
    public function getDocument($folder,$name)
    {
    	try {
			$file = File::get(public_path()."/driver/".$folder."/document/".$name);
			$response = Response::make($file, 200);
			$response->header('Content-Type', 'application/image');
			return $response;
    	} catch (Exception $e) {
    		Exceptions::exception($e);		
    	}
    }
    
}