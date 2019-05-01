<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\ResponseMessage;
use App\Helper\Exceptions;
use App\Model\Customer\Tripdetail;
use App\Model\Notificationlog;
use App\Model\Trippayment;
use Validator;
use Auth;

class PaymentController extends Controller
{
	public function make_payment(Request $request)
	{
		try {
			$rules = [
    			'customer_id' => 'required',
    			'payment_mode' => 'required|in:cash,card,wallet,paypal,mpesa',
    			'trip_id' => 'required',
    			'transaction_id' => '',
    			'payment_response' => '',
    			'payment' => 'required|in:success,pending,fail,other',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.',
				'payment_mode.required' => 'Please sent payment mode.',
				'payment_mode.in' => 'Payment mode must be cash,card,wallet,paypal,mpesa.',
				'trip_id.required' => 'Please sent trip id.',
				'payment.required' => 'Please sent payment status.',
				'payment.in' => 'Payment status success,pending,fail,other.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$payment_mode = trim(strip_tags($request->payment_mode));
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$transaction_id = trim(strip_tags($request->transaction_id));
	        	$payment_response = trim(strip_tags($request->payment_response));
	        	$payment = trim(strip_tags($request->payment));

	        	if($payment_mode==="paypal"){
	        		$trippayment = new Trippayment;
	        		$trippayment->trip_id = $trip_id;
	        		$trippayment->customer_id = $customer_id;
	        		$trippayment->payment_mode = $payment_mode;
	        		$trippayment->transaction_id = $transaction_id;
	        		$trippayment->payment = $payment;
	        		$trippayment->payment_response = $payment_response;
	        		if($trippayment->save()){
	        			$trip = Tripdetail::find($trip_id);
	        			$trip->payment = $payment;
	        			$trip->payment_mode = $payment_mode;
	        			if($trip->save()){
	        				Notificationlog::create("customer",$customer_id,"Payment success for ".$trip->drop_address." trip.","yes");
	        				$response = array();
	        				ResponseMessage::success("Payment success.",$response);
	        			} else{
	        				ResponseMessage::error("Something went wrong.");		
	        			}
	        		} else{
	        			ResponseMessage::error("Payment detail not store.");	
	        		}
	        	} else{
	        		ResponseMessage::error("Payment mode not support.");
	        	}
	        }
		} catch (Exception $e) {
			Exceptions::exception($e);
		}
	}
}
