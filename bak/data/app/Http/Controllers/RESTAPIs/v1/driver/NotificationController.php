<?php

namespace App\Http\Controllers\RESTAPIs\v1\driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\ResponseMessage;
use App\Helper\Exceptions;
use App\Model\Notificationlog;
use Response;
use Validator;
use App\Model\Driver\Driver;

class NotificationController extends Controller
{
    public function get_notification(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	if(Driver::where('id',$driver_id)->exists()){
	        		$response = Notificationlog::select('title','created_at')->where('user_type','driver')->where('user_id',$driver_id)->orderby('created_at','DESC')->get();
	        		ResponseMessage::success("Notification log.",$response);
	        	} else{
	        		ResponseMessage::error("Driver not found.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
