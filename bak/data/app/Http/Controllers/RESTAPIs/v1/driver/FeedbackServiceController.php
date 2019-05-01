<?php

namespace App\Http\Controllers\RESTAPIs\v1\driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Exceptions;
use App\Helper\ResponseMessage;
use App\Model\Customer\Tripdetail;
use App\Model\Driver\Feedback;
use App\User;
use Validator;

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
	        	/* VARIABLE */
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$feedback = trim(strip_tags($request->feedback));
	        	$rate = $request->rate;

	        	if($feedback !="" || $rate!=""){
		        	if(Tripdetail::where('key',$trip_id)->where('customer_id',$customer_id)->where('driver_id',$driver_id)->where('status','complete')->exists()){
		        		$feedbackModel = New Feedback;
		        		$feedbackModel->trip_id = $trip_id;
		        		$feedbackModel->driver_id = $driver_id;
		        		$feedbackModel->customer_id = $customer_id;
		        		$feedbackModel->feedback = $feedback;
		        		$feedbackModel->rate = $rate;
		        		if($feedbackModel->save()){
		        			$customer_avg_rating = Feedback::where('customer_id',$customer_id)->avg('rate');
		        			$customer = User::findOrFail($customer_id);
		        			if($customer)
		        			{
		        				$customer->rating = number_format((float)$customer_avg_rating, 1, '.', '');
		        				if($customer->save()){
		        					ResponseMessage::success("Thank you for your feedback");
		        				} else{
	        						ResponseMessage::error("Customer rating not update.");
		        				}
		        			} else{
		        				ResponseMessage::error("Customer rating not update.");
		        			}
		        		} else{
		        			ResponseMessage::error("Feedback not save.");	
		        		}
		        	} else{
		        		ResponseMessage::error("Trip detail not found.");
		        	}
	        	} else{
	        		ResponseMessage::success("Trip complete.");
	        	}

	        }	
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
