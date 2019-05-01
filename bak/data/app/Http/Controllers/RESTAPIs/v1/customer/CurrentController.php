<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Exceptions;
use App\Helper\ResponseMessage;
use App\Model\Customer\Tripdetail;
use App\Model\Driver\Driver;
use App\User;
use Validator;

class CurrentController extends Controller
{
    public function get_current(Request $request)
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
	        	/*variable*/
	        	$customer_id = strip_tags(trim($request->customer_id));
	        	$response = "";
	        	if(User::where('id',$customer_id)->exists()){
	        		$tripdetail = Tripdetail::where('customer_id',$customer_id)->orderby('created_at','DESC')->get()->first();
	        		if($tripdetail){
	        			if($tripdetail->status == "booked"){
	        				$response = Tripdetail::select('trip_details.key','trip_details.status','trip_details.fare_duration','trip_details.driver_id','trip_details.customer_id','trip_details.pickup_address','trip_details.pickup_latitude','trip_details.pickup_longitude','trip_details.drop_address','trip_details.drop_latitude','trip_details.drop_longitude','vehicle_types.name as vehicle_type_name','vehicle_categorys.name as vehicle_category_name','trip_details.payment_mode','trip_details.pickup_time','trip_details.drop_time','trip_details.total_price','countrys.currency_sign','drivers.name','drivers.country_code','drivers.contact_no','drivers.profile','drivers.rating','drivers.vehicle_number','available_drivers.latitude as driver_latitude','available_drivers.longitude as driver_longitude')
	        					->leftjoin('drivers','drivers.id','trip_details.driver_id')
	        					->leftjoin('countrys','countrys.currency_code','=','trip_details.currency_code')
	        					->leftjoin('vehicle_types','vehicle_types.id','=','trip_details.vehicle_type_id')
	        					->leftjoin('vehicle_categorys','vehicle_categorys.id','=','trip_details.vehicle_category_id')
	        					->leftjoin('available_drivers','available_drivers.driver_id','=','trip_details.driver_id')
	        					->where('trip_details.customer_id',$customer_id)
	        					->where('trip_details.status','booked')
	        					->get()
	        					->first();
	        			} else if($tripdetail->status == "ongoing"){
	        				$response = Tripdetail::select('trip_details.key','trip_details.status','trip_details.fare_duration','trip_details.driver_id','trip_details.customer_id','trip_details.pickup_address','trip_details.pickup_latitude','trip_details.pickup_longitude','trip_details.drop_address','trip_details.drop_latitude','trip_details.drop_longitude','vehicle_types.name as vehicle_type_name','vehicle_categorys.name as vehicle_category_name','trip_details.payment_mode','trip_details.pickup_time','trip_details.drop_time','trip_details.total_price','countrys.currency_sign','drivers.name','drivers.country_code','drivers.contact_no','drivers.profile','drivers.rating','drivers.vehicle_number')
	        					->leftjoin('drivers','drivers.id','trip_details.driver_id')
	        					->leftjoin('countrys','countrys.currency_code','=','trip_details.currency_code')
	        					->leftjoin('vehicle_types','vehicle_types.id','=','trip_details.vehicle_type_id')
	        					->leftjoin('vehicle_categorys','vehicle_categorys.id','=','trip_details.vehicle_category_id')
	        					->where('trip_details.customer_id',$customer_id)
	        					->where('trip_details.status','ongoing')
	        					->get()
	        					->first();
	        			} else if($tripdetail->status == "complete"){
	        				$response = Tripdetail::select('trip_details.key','trip_details.status','trip_details.fare_duration','trip_details.driver_id','trip_details.customer_id','trip_details.pickup_address','trip_details.pickup_latitude','trip_details.pickup_longitude','trip_details.drop_address','trip_details.drop_latitude','trip_details.drop_longitude','vehicle_types.name as vehicle_type_name','vehicle_categorys.name as vehicle_category_name','trip_details.payment_mode','trip_details.pickup_time','trip_details.drop_time','trip_details.total_price','countrys.currency_sign','drivers.name','drivers.country_code','drivers.contact_no','drivers.profile','drivers.rating','drivers.vehicle_number')
	        					->leftjoin('drivers','drivers.id','trip_details.driver_id')
	        					->leftjoin('countrys','countrys.currency_code','=','trip_details.currency_code')
	        					->leftjoin('vehicle_types','vehicle_types.id','=','trip_details.vehicle_type_id')
	        					->leftjoin('vehicle_categorys','vehicle_categorys.id','=','trip_details.vehicle_category_id')
	        					->where('trip_details.customer_id',$customer_id)
	        					->where('trip_details.status','complete')
	        					->get()
	        					->first();
	        			} else{
	        				$response = "";
	        			}
	        			ResponseMessage::success("Customer previous trip",$response);
	        		} else{
	        			ResponseMessage::error("Not any trip yet.");
	        		}
	        	} else{
	        		ResponseMessage::error("Customer not found");
	        	}
	        }
    	} catch (Exception $e) {
    		
    	}
    }
}