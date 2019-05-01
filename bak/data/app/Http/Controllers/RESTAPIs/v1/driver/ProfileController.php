<?php

namespace App\Http\Controllers\RESTAPIs\v1\driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\ResponseMessage;
use App\Helper\Exceptions;
use App\Helper\SMSHelper;
use App\Model\Changecontact;
use App\Model\Changeemail;
use App\Model\Driver\Driver;
use App\Helper\RandomHelper;
use App\Helper\MailHelper;
use Auth;
use Validator;
use Hash;
use DB;

class ProfileController extends Controller
{
	/**
       * 
       * get a driver detail
       *
       * @param integer $driver_id  driver id.
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function get_profile(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required'
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	if(Driver::where('id',$driver_id)->exists()){
	        		$driver = Driver::select('drivers.id','drivers.name','drivers.email','drivers.country_code','drivers.contact_no','drivers.profile','drivers.invite_code','drivers.invited_code','drivers.vehicle_type','drivers.vehicle_category','drivers.bank_account_number','drivers.bank_name','drivers.account_holder_name','drivers.address','drivers.vehicle_number','vehicle_categorys.name as vehicle_category_name')
	        					->where('drivers.id',$driver_id)
	        					->leftjoin('vehicle_categorys','vehicle_categorys.id','drivers.vehicle_category')
        						->get()
        						->first();
		        	ResponseMessage::success("Driver Details",$driver);
	        	} else{
	        		ResponseMessage::error("Driver not found");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       * 
       * update a driver
       *
       * @param integer $driver_id  driver id.
       * @param string $name  driver name.
       * @param string $image  profile image. (Optional)
       * @param string $bank_account_number  Driver Bank Account Number.
       * @param string $bank_name  Driver Bank Name.
       * @param string $account_holder_name  Driver Bank Account Holder Name.
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function update(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'name' => 'required|string|max:50',
    			'image' => 'image',
    			'bank_account_number' => 'required|max:30',
    			'bank_name'=>'required|max:60',
    			'account_holder_name'=>'required|max:60',
    			'address' => 'required',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'name.required' => 'Please enter name.',
                'image.image' => 'Profile image type invalid.',
                'bank_account_number.required'=>'Please enter bank account number.',
                'bank_name.required'=>'Please enter bank name.',
                'account_holder_name.required'=>'Please enter account holder name.',
                'address.required'=>'Please enter address.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$name = trim(strip_tags($request->name));
	        	$bank_account_number = trim(strip_tags($request->bank_account_number));
	        	$bank_name = trim(strip_tags($request->bank_name));
	        	$account_holder_name = trim(strip_tags($request->account_holder_name));
	        	$address = trim(strip_tags($request->address));

	        	if(Driver::where('id',$driver_id)->exists()){
	        		$driver = Driver::find($driver_id);
	        		if($driver){
	        			$driver->name = $name;
	        			$driver->bank_account_number = $bank_account_number;
	        			$driver->bank_name = $bank_name;
	        			$driver->account_holder_name = $account_holder_name;
	        			$driver->address = $address;
			        	if($request->hasFile('image')) {
			        		$time = md5(time());
			        		$file = $request->image;
			        		$extension = $file->getClientOriginalExtension();
			        		$profile_image = $time.'.'.$file->getClientOriginalExtension();
			        		$file->move(public_path('driver/'.$driver->invite_code.'/profile/'),$profile_image);
			        		$profile = 'driver/'.$driver->invite_code.'/profile/'.$profile_image;
			        		$driver->profile =$profile;
			        	}
			        	if($driver->save()){
			        		$response = Driver::select('drivers.id','drivers.name','drivers.email','drivers.country_code','drivers.contact_no','drivers.profile','drivers.invite_code','drivers.invited_code','drivers.vehicle_type','drivers.vehicle_category','drivers.bank_account_number','drivers.bank_name','drivers.account_holder_name','drivers.address','drivers.vehicle_number','vehicle_categorys.name as vehicle_category_name')
			        				->where('drivers.id',$driver->id)
			        				->leftjoin('vehicle_categorys','vehicle_categorys.id','drivers.vehicle_category')
			        				->get()
			        				->first();
			        		ResponseMessage::success("Profile Update successfully.",$response);
			        	} else{
			        		ResponseMessage::error("Something went wrong please try again.");	
			        	}
	        		} else{
	        			ResponseMessage::error("Driver not found");	
	        		}
	        	} else{
	        		ResponseMessage::error("Driver not found");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       * 
       * update a contact number
       *
       * @param integer $driver_id  driver id.
       * @param string $country_code  country code.
       * @param string $mobile  driver mobile number. 
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function change_contact_no(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'country_code' => "required",
    			'mobile' => 'required|numeric|digits_between:6,15|unique:drivers,contact_no',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'country_code.required' => 'Please enter country code.',
				'mobile.required' => 'Please enter contact number',
                'mobile.digits_between' => 'Please enter only digits and maximum 15 digits.',
                'mobile.unique' => 'Contact number is already exists.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$country_code = trim(strip_tags($request->country_code));
	        	$contact_no = trim(strip_tags($request->mobile));
	        	$user_type = "driver";
	        	$status = "notverified";

	        	if(Driver::where('id',$driver_id)->exists()){
	        		$driver = Driver::where('id',$driver_id)->get()->first();
	        		if($driver->contact_no!=$contact_no){
		        		$SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
	                	$otp = $SMSresponse['otp'];
	                	// MailHelper::sendOTP($driver,$otp);
	                	if($SMSresponse!="false"){
		        			$changecontact = New Changecontact;
	                		$changecontact->user_type = $user_type;
	                		$changecontact->user_id = $driver_id;
	                		$changecontact->country_code = $country_code;
	                		$changecontact->new_contact_no = $contact_no;
	                		$changecontact->otp = $otp;
	                		$changecontact->status = $status;
	                		if($changecontact->save()){
	                			$response = array();
	                			ResponseMessage::success("Otp sent successfully",$response);
	                		} else{
	                			ResponseMessage::error("Something went wrong please try again.");
	                		}
	                	} else{
	                		ResponseMessage::error("OTP not sent please try again.");
	                	}
	        		} else{
	        			ResponseMessage::error("Driver already verified.");
	        		}
	        	} else{
	        		ResponseMessage::error("Driver not found");
	        	}
        	}
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       * 
       * verify a contact number
       *
       * @param integer $driver_id  driver id.
       * @param string $country_code  country code.
       * @param string $mobile  driver mobile number. 
       * @param string $otp  otp. 
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function verify_new_contact(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'country_code' => "required",
    			'mobile' => 'required|numeric|digits_between:6,15',
    			'otp' => 'required',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'country_code.required' => 'Please enter country code.',
				'mobile.required' => 'Please enter contact number',
                'mobile.digits_between' => 'Please enter only digits and maximum 15 digits.',
                'otp.required' => 'Please enter otp.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$country_code = trim(strip_tags($request->country_code));
	        	$contact_no = trim(strip_tags($request->mobile));
	        	$otp = trim(strip_tags($request->otp));
	        	$user_type = "driver";
	        	$status = "verified";
	        	$updated_at = date('Y-m-d H:i:s');

	        	if(Driver::where('id',$driver_id)->exists()){
	        		if(Changecontact::where('user_type',$user_type)->where('country_code',$country_code)->where('new_contact_no',$contact_no)->exists()){
	        			$changecontact = Changecontact::where('user_type',$user_type)->where('country_code',$country_code)->where('new_contact_no',$contact_no)->where('status','!=','verified')->orderby('created_at','DESC')->get()->first();
	        			if($changecontact){
	        				if($changecontact->otp==$otp){
		        				$driver = Driver::find($driver_id);
		        				$driver->country_code = $changecontact->country_code;
		        				$driver->contact_no = $changecontact->new_contact_no;
		        				if($driver->save()){
		        					$status_change = Changecontact::where('user_type',$user_type)->where('country_code',$country_code)->where('new_contact_no',$contact_no)->orderby('created_at','DESC')->where('otp',$otp)->update(['status'=>$status,'updated_at'=>$updated_at]);
		        					if($status_change){
		        						ResponseMessage::success("Contact no successfully change.",["contact_no"=>$changecontact->new_contact_no]);	
		        					}
		        				} else{
		        					ResponseMessage::error("Something went wrong.");
		        				}
	        				} else{
	        					ResponseMessage::error("Otp not match.");
	        				}
	        			} else{
	        				ResponseMessage::error("Contact number not found.");
	        			}
	        		} else{
	        			ResponseMessage::error("Contact change request not get.");
	        		}
	        	} else{
	        		ResponseMessage::error("Driver not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       * 
       * change password driver
       *
       * @param integer $driver_id  driver id.
       * @param string $old_password  old password.
       * @param string $new_password  new password. 
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function change_password(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'old_password' => "required",
    			'new_password' => 'required|min:6',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'old_password.required' => 'Please enter old password.',
				'new_password.required' => 'Please enter new password.',
                'new_password.min' => 'New password minimum 6 character.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$old_password = trim(strip_tags($request->old_password));
	        	$new_password = trim(strip_tags($request->new_password));
	        	if(Driver::where('id',$driver_id)->exists()){
	        		$driver_info =  Driver::where('id',$driver_id)->get()->first();
	        		if(Hash::check($old_password,$driver_info->password)){
	        			$hash_password = Hash::make($new_password);
	        			$driver = Driver::find($driver_id);
	        			$driver->password = $hash_password;
	        			if($driver->save()){
	        				ResponseMessage::success("Successfully password change");
	        			} else{
	        				ResponseMessage::error("Something went wrong please try again.");		
	        			}
	        		} else{
	        			ResponseMessage::error("Old password not match.");	
	        		}
	        	} else{
	        		ResponseMessage::error("Driver not found");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /**
       * 
       * update a email
       *
       * @param integer $driver_id  driver id.
       * @param string $email  country code.
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function change_email(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'email' => "required|email|unique:drivers,email",
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'email.required' => 'Please enter email.',
				'email.unique' => 'Email is already exists.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$email = trim(strip_tags($request->email));
	        	$user_type = "driver";
	        	$status = "notverified";

	        	if(Driver::where('id',$driver_id)->exists()){
	        		$driver = Driver::where('id',$driver_id)->get()->first();
	        		if($driver->email!=$email){
	                	$otp = RandomHelper::randomOTP();
	                	$MAILresponse = MailHelper::sendChangeOTP($driver,$email,$otp);
		        		// $SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
	                	if($MAILresponse!="false"){
		        			$changemail = New Changeemail;
	                		$changemail->user_type = $user_type;
	                		$changemail->user_id = $driver_id;
	                		$changemail->email = $email;
	                		$changemail->otp = $otp;
	                		$changemail->status = $status;
	                		if($changemail->save()){
	                			$response = array();
	                			ResponseMessage::success("Otp sent successfully",$response);
	                		} else{
	                			ResponseMessage::error("Something went wrong please try again.");
	                		}
	                	} else{
	                		ResponseMessage::error("OTP not sent please try again.");
	                	}
	        		} else{
	        			ResponseMessage::error("Driver already verified.");
	        		}
	        	} else{
	        		ResponseMessage::error("Driver not found");
	        	}
        	}
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }


    /**
       * 
       * verify a email
       *
       * @param integer $driver_id  driver id.
       * @param string $email  country code.
       * @param string $otp  otp. 
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function verify_new_email(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'email' => "required|email",
    			'otp' => 'required',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'email.required' => 'Please enter email.',
				'email.email' => 'invalid email address.',
                'otp.required' => 'Please enter otp.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$email = trim(strip_tags($request->email));
	        	$otp = trim(strip_tags($request->otp));
	        	$user_type = "driver";
	        	$status = "verified";
	        	$updated_at = date('Y-m-d H:i:s');

	        	if(Driver::where('id',$driver_id)->exists()){
	        		if(Changeemail::where('user_type',$user_type)->where('user_id',$driver_id)->where('email',$email)->exists()){
	        			$changeemail = Changeemail::where('user_type',$user_type)->where('user_id',$driver_id)->where('email',$email)->where('status','!=','verified')->orderby('created_at','DESC')->get()->first();
	        			if($changeemail){
	        				if($changeemail->otp==$otp){
		        				$driver = Driver::find($driver_id);
		        				$driver->email = $changeemail->email;
		        				if($driver->save()){
		        					$status_change = Changeemail::where('user_type',$user_type)->where('user_id',$driver_id)->where('email',$email)->where('status','!=','verified')->orderby('created_at','DESC')->where('otp',$otp)->update(['status'=>$status,'updated_at'=>$updated_at]);
		        					if($status_change){
		        						ResponseMessage::success("Email successfully change.",["email"=>$changeemail->email]);
		        					}
		        				} else{
		        					ResponseMessage::error("Something went wrong.");
		        				}
	        				} else{
	        					ResponseMessage::error("Otp not match.");
	        				}
	        			} else{
	        				ResponseMessage::error("Email not found.");
	        			}
	        		} else{
	        			ResponseMessage::error("Email request not get.");
	        		}
	        	} else{
	        		ResponseMessage::error("Driver not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    
}