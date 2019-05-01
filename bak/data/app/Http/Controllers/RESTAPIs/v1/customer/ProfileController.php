<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\ResponseMessage;
use App\Helper\Exceptions;
use App\Helper\SMSHelper;
use App\Model\Changecontact;
use App\User;
use Auth;
use Validator;
use DB;
use Hash;

class ProfileController extends Controller
{
    public function get_profile(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required'
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	if(User::where('id',$customer_id)->exists()){
	        		$customer = User::where('id',$customer_id)->get()->first();
		        	ResponseMessage::success("Customer Details",$customer);
	        	} else{
	        		ResponseMessage::error("Customer not found");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function update(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'name' => 'required|string|max:255',
    			'mobile' => 'required|numeric|digits_between:6,15',  //|unique:users,contact_no
    			'image' => 'image',
			];
			$customer_id = trim(strip_tags($request->customer_id));
			if(User::where('id',$customer_id)->exists()){
	        	$customer = User::where('id',$customer_id)->get()->first();
	        	if($customer->contact_no!=trim(strip_tags($request->mobile))){
	        		$rules['mobile'] = 'required|numeric|digits_between:1,15|unique:users,contact_no';
	        	}
	        }
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'name.required' => 'Please enter name.',
				'mobile.required' => 'Please enter contact number',
                'mobile.digits_between' => 'Please enter only digits and maximum 15 digits.',
                'mobile.unique' => 'Contact number is already exists.',
                'image.image' => 'Profile image type invalid.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$name = trim(strip_tags($request->name));
	        	$contact_no = trim(strip_tags($request->mobile));
	        	if(User::where('id',$customer_id)->exists()){
	        		$customer = User::find($customer_id);
	        		if($customer){
	        			$customer->name = $name;
	        			$customer->contact_no = $contact_no;
			        	if($request->hasFile('image')) {
			        		$time = md5(time());
			        		$file = $request->image;
			        		$extension = $file->getClientOriginalExtension();
			        		$profile_image = $time.'.'.$file->getClientOriginalExtension();
			        		$file->move(public_path('user/'.$customer->invite_code.'/profile/'),$profile_image);
			        		$profile = 'user/'.$customer->invite_code.'/profile/'.$profile_image;
			        		$customer->profile =$profile;
			        	}
			        	if($customer->save()){
			        		$response = User::select('id','name','email','country_code','contact_no','profile','invite_code')->where('id',$customer->id)->get()->first();
			        		ResponseMessage::success("Profile Update successfully.",$response);
			        	} else{
			        		ResponseMessage::error("Something went wrong please try again.");	
			        	}
	        		} else{
	        			ResponseMessage::error("Customer not found");	
	        		}
	        	} else{
	        		ResponseMessage::error("Customer not found");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function change_contact_no(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'country_code' => "required",
    			'mobile' => 'required|numeric|digits_between:6,15|unique:users,contact_no',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
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
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$country_code = trim(strip_tags($request->country_code));
	        	$contact_no = trim(strip_tags($request->mobile));
	        	$user_type = "customer";
	        	$status = "notverified";

	        	if(User::where('id',$customer_id)->exists()){
	        		$customer = User::where('id',$customer_id)->get()->first();
	        		if($customer->contact_no!=$contact_no){
		        		$SMSresponse = SMSHelper::sendOTP($country_code,$contact_no);
	                	$otp = $SMSresponse['otp'];
	                	// MailHelper::sendOTP($customer,$otp);
	                	if($SMSresponse!="false"){
		        			$changecontact = New Changecontact;
	                		$changecontact->user_type = $user_type;
	                		$changecontact->user_id = $customer_id;
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
	        			ResponseMessage::error("Contact already verified.");
	        		}
	        	} else{
	        		ResponseMessage::error("Customer not found");
	        	}
        	}
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function verify_new_contact(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'country_code' => "required",
    			'mobile' => 'required|numeric|digits_between:6,15',
    			'otp' => 'required',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
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
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$country_code = trim(strip_tags($request->country_code));
	        	$contact_no = trim(strip_tags($request->mobile));
	        	$otp = trim(strip_tags($request->otp));
	        	$user_type = "customer";
	        	$status = "verified";
	        	$updated_at = date('Y-m-d H:i:s');

	        	if(User::where('id',$customer_id)->exists()){
	        		if(Changecontact::where('user_type',$user_type)->where('country_code',$country_code)->where('new_contact_no',$contact_no)->exists()){
	        			$changecontact = Changecontact::where('user_type',$user_type)->where('country_code',$country_code)->where('new_contact_no',$contact_no)->where('status','!=','verified')->orderby('created_at','DESC')->get()->first();
	        			if($changecontact){
	        				if($changecontact->otp==$otp){
		        				$customer = User::find($customer_id);
		        				$customer->country_code = $changecontact->country_code;
		        				$customer->contact_no = $changecontact->new_contact_no;
		        				if($customer->save()){
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
	        		ResponseMessage::error("Customer not found");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function change_password(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'old_password' => "required",
    			'new_password' => 'required|min:6',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'old_password.required' => 'Please enter old password.',
				'new_password.required' => 'Please enter new password.',
                'new_password.min' => 'New password minimum 6 character.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$old_password = trim(strip_tags($request->old_password));
	        	$new_password = trim(strip_tags($request->new_password));
	        	if(User::where('id',$customer_id)->exists()){
	        		$customer_info =  User::where('id',$customer_id)->get()->first();
	        		if(Hash::check($old_password,$customer_info->password)){
	        			$hash_password = Hash::make($new_password);
	        			$customer = User::find($customer_id);
	        			$customer->password = $hash_password;
	        			if($customer->save()){
	        				ResponseMessage::success("Successfully password change");
	        			} else{
	        				ResponseMessage::error("Something went wrong please try again.");		
	        			}
	        		} else{
	        			ResponseMessage::error("Old password not match.");	
	        		}
	        	} else{
	        		ResponseMessage::error("Customer not found");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
