<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Exceptions;
use App\Helper\ResponseMessage;
use App\Model\Customer\Tripdetail;
use App\Model\Customer\Feedback;
use App\Model\Driver\Driver;
use Validator;
use App\User;

class FeedbackServiceController extends Controller
{
    public function feedback_save(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'driver_id' => 'required',
    			'trip_id' => 'required',
    			'feedback' => '',
    			'rate' => ''
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'driver_id.required' => 'Please sent driver id.',
				'trip_id.required' => 'Please sent trip id.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$feedback = trim(strip_tags($request->feedback));
	        	$rate = $request->rate;

	        	if(Tripdetail::where('key',$trip_id)->where('customer_id',$customer_id)->where('driver_id',$driver_id)->where('status','complete')->exists()){
	        		$feedbackModel = New Feedback;
	        		$feedbackModel->trip_id = $trip_id;
	        		$feedbackModel->driver_id = $driver_id;
	        		$feedbackModel->customer_id = $customer_id;
	        		$feedbackModel->feedback = $feedback;
	        		$feedbackModel->rate = $rate;
	        		if($feedbackModel->save()){
	        			$driver_avg_rating = Feedback::where('driver_id',$driver_id)->avg('rate');
	        			$driver = Driver::findOrFail($driver_id);
	        			if($driver)
	        			{
	        				$driver->rating = number_format((float)$driver_avg_rating, 1, '.', '');
	        				if($driver->save()){
	        					ResponseMessage::success("Feedback success submit. Thank you");
	        				} else{
        						ResponseMessage::error("Driver rating not update.");
	        				}
	        			} else{
	        				ResponseMessage::error("Driver rating not update.");
	        			}
	        		} else{
	        			ResponseMessage::error("Feedback not save.");	
	        		}
	        	} else{
	        		ResponseMessage::error("Trip detail not found.");
	        	}

	        	// if(Tripdetail::where('key',$trip_id)->where('customer_id',$customer_id)->where('driver_id',$driver_id)->where('status','complete')->exists())
	        	// {
		        // 	if(Driver::where('id',$driver_id)->exists())
		        // 	{
		        // 		$feedback = New Feedback;
		        // 		$feedback->trip_id = $trip_id;
		        // 		$feedback->driver_id = $driver_id;
		        // 		$feedback->customer_id = $customer_id;
		        // 		$feedback->feedback = $feedback;
		        // 		$feedback->rate = $rate;
		        // 		if($feedback->save()
		        // 		{
		        // 			$driver_avg_rating = Feedback::where('driver_id',$driver_id)->avg('rate');
		        // 			$driver = Driver::findOrFail($driver_id);
		        // 			if($driver)
		        // 			{
		        // 				$driver->rating = $driver_avg_rating;
		        // 				if($driver->save()){
		        // 					ResponseMessage::success("Feedback success submit. Thank you");
		        // 				} else{
	        	// 					ResponseMessage::error("Driver rating not update.");
		        // 				}
		        // 			} else{
		        // 				ResponseMessage::error("Driver rating not update.");
		        // 			}
		        // 		} else{
		        // 			ResponseMessage::error("Feedback not save.");	
		        // 		}
		        // 	} else{
		        // 		ResponseMessage::error("Driver not exits.");
		        // 	}
	        	// } else{
	        	// 	ResponseMessage::error("Trip detail not found.");
	        	// }
	        }	
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
