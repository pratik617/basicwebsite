<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Notificationlog;
use App\Helper\ResponseMessage;
use Validator;
use Auth;
use App\User;

class NotificationController extends Controller
{
    public function get_notification(Request $request)
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
	        		$response = Notificationlog::select('title','created_at')->where('user_type','customer')->where('user_id',$customer_id)->orderby('created_at','DESC')->get();
	        		ResponseMessage::success("Notification log.",$response);
	        	} else{
	        		ResponseMessage::error("Customer not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
