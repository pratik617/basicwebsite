<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Customer\Sos;
use App\Helper\Exceptions;
use App\Helper\SMSHelper;
use App\Helper\ResponseMessage;
use Validator;
use App\User;

class SosController extends Controller
{
    public function create_sos(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'name' => 'required|max:255',
    			'contact_no' => 'required|numeric',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'name.required' => 'Please sent name.',
				'name.max' => 'Name data too large.',
				'contact_no.required' => 'Please sent contact no.',
				'contact_no.numeric' => 'Contact no must be digit only.',
				'contact_no.max' => 'Contact no maxinum 15 digit.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$name = trim(strip_tags($request->name));
	        	$contact_no = trim(strip_tags($request->contact_no));
	        	$country_code = trim(strip_tags($request->country_code));

	        	if(User::where('id',$customer_id)->exists()){
	        		$sos_count = Sos::where('customer_id',$customer_id)->get()->count();
	        		if($sos_count<5){
	        			// $country_code = User::where('id',$customer_id)->get()->first()->country_code;
		        		$sos = New Sos;
		        		$sos->customer_id = $customer_id;
		        		$sos->country_code = $country_code;
		        		$sos->name = $name;
		        		$sos->contact_no = $contact_no;
		        		if($sos->save()){
		        			ResponseMessage::success("SOS member Add successfully",$sos);
		        		} else{
		        			ResponseMessage::error("Something went wrong.");	
		        		}
	        		} else{
	        			ResponseMessage::error("Maxinum 5 sos create.");
	        		}
	        	} else{
	        		ResponseMessage::error("User not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function get_sos(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	if(User::where('id',$customer_id)->exists()){
	        		$sos = Sos::select('id','name','country_code','contact_no')->where('customer_id',$customer_id)->get();
	        		ResponseMessage::success("Sos list.",$sos);
	        	} else{
	        		ResponseMessage::error("User not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function delete_sos(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'sos_id' => 'required',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'sos_id.required' => 'Please sent sos id.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$sos_id = trim(strip_tags($request->sos_id));
	        	$response = array();
	        	if(User::where('id',$customer_id)->exists()){
	        		$sos = Sos::find($sos_id);
	        		if($sos){
		        		if($sos->delete()){
		        			ResponseMessage::success("SOS Delete.",$response);
		        		} else{
		        			ResponseMessage::error("Something went wrong.");
		        		}
	        		} else{
	        			ResponseMessage::error("SOS not found.");
	        		}
	        	} else{
	        		ResponseMessage::error("User not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function edit_sos(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'sos_id' => 'required',
    			'name' => 'required|max:255',
    			'contact_no' => 'required|numeric',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'sos_id.required' => 'Please sent sos id.',
				'name.required' => 'Please sent name.',
				'name.max' => 'Name data too large.',
				'contact_no.required' => 'Please sent contact no.',
				'contact_no.numeric' => 'Contact no must be digit only.',
				'contact_no.max' => 'Contact no maxinum 15 digit.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$sos_id = trim(strip_tags($request->sos_id));
	        	$name = trim(strip_tags($request->name));
	        	$country_code = trim(strip_tags($request->country_code));
	        	$contact_no = trim(strip_tags($request->contact_no));
	        	$response = array();

	        	if(User::where('id',$customer_id)->exists()){
	        		// $country_code = User::where('id',$customer_id)->get()->first()->country_code;
	        		$sos = Sos::find($sos_id);
	        		if($sos){
		        		$sos->name = $name;
		        		$sos->country_code = $country_code;
		        		$sos->contact_no = $contact_no;
		        		if($sos->save()){
		        			ResponseMessage::success("SOS member Update successfully",$response);
		        		} else{
		        			ResponseMessage::error("Something went wrong.");
		        		}
	        		} else{
	        			ResponseMessage::error("SOS not found.");
	        		}
	        	} else{
	        		ResponseMessage::error("User not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function send_sos(Request $request)
    {

    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'trip_id' => 'required',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'trip_id.required' => 'Please sent trip id.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$sos_link = $request->root().'/track/'.$trip_id;

	        	if(User::where('id',$customer_id)->exists()){
	        		$getSosList = Sos::where('customer_id',$customer_id)->get();
	        		if(count($getSosList)!=0){
	        			foreach ($getSosList as $sos) {
	        				$response = SMSHelper::sendSOSLink($sos->country_code,$sos->contact_no,$sos_link);
	        				if($response=="true"){
	        				} else{
	        				}
	        			}
	        			ResponseMessage::success("Sos send successfully.");	
	        		} else{
	        			ResponseMessage::error("Sos not found.");	
	        		}
	        	} else{
	        		ResponseMessage::error("User not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

}
