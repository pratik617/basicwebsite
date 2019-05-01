<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Exceptions;
use App\Helper\ResponseMessage;
use App\Model\Customer\Tripdetail;
use App\Model\Customer\Retaindetail;
use App\Model\Customer\Friendsdetail;
use App\Model\Vehicletype;
use App\Model\Notificationlog;
use App\Model\Vehiclecategory;
use App\Model\Tripcancel;
use App\User;
use Validator;
use Auth;
use DB;

class TripServiceController extends Controller
{
	/**
       * 
       * Customer Past Trip Details
       *
       * @param int $customer_id customer id
       * @return json
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */
    public function past_trip_details(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'fromdate' => 'date_format:dd/mm/yyyy',
    			'todate' => 'date_format:dd/mm/yyyy',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$fromdate = trim(strip_tags($request->fromdate));
	        	$todate = trim(strip_tags($request->todate));
	        	/*CHECK USER EXISTS*/
	        	if(User::where('id',$customer_id)->exists()){
	        		/*GET PAST TRIP DETAILS*/
					$tripdetails = DB::table('trip_details')
									->select([
										'trip_details.key',
										'trip_details.pickup_address',
										'trip_details.drop_address',
										'trip_details.fare_distance',
										'trip_details.total_price',
										'trip_details.pickup_time',
										'trip_details.payment_mode',
										'trip_details.map_image',
										'trip_details.status',
										'drivers.name as driver_name',
										'drivers.country_code',
										'drivers.contact_no',
										'drivers.vehicle_number',
										'drivers.profile as driver_profile',
										'vehicle_categorys.name as vehicle_category_name',
										'countrys.currency_sign'
									])
									->leftjoin('countrys','countrys.currency_code','=','trip_details.currency_code')
									->leftjoin('drivers','drivers.id','=','trip_details.driver_id')
									->leftjoin('vehicle_categorys','vehicle_categorys.id','=','trip_details.vehicle_category_id')
									->whereIn('trip_details.status',['complete','cancel'])
									->where('trip_details.customer_id',$customer_id)
									->orderBy('trip_details.drop_time','DESC')
									->get();
	        		ResponseMessage::success("Past trip details.",$tripdetails);
	        	} else{
	        		ResponseMessage::error("User not exits.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
    public function ongoing_trip(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
			];
			$customeMessage = [
				'customer_id.required' => 'Please sent customer id.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	/*CHECK USER EXISTS*/
	        	if(User::where('id',$customer_id)->exists()){
	        		$ongoing = Tripdetail::select('trip_details.key','trip_details.driver_id','trip_details.customer_id','trip_details.pickup_address','trip_details.pickup_latitude','trip_details.pickup_longitude','trip_details.drop_address','trip_details.drop_latitude','trip_details.drop_longitude','vehicle_types.name as vehicle_type_name','vehicle_categorys.name as vehicle_category_name','trip_details.payment_mode','trip_details.pickup_time','trip_details.drop_time','trip_details.total_price','countrys.currency_sign','drivers.name','drivers.country_code','drivers.contact_no','drivers.profile','drivers.rating')
	        					->leftjoin('drivers','drivers.id','trip_details.driver_id')
	        					->leftjoin('countrys','countrys.currency_code','=','trip_details.currency_code')
	        					->leftjoin('vehicle_types','vehicle_types.id','=','trip_details.vehicle_type_id')
	        					->leftjoin('vehicle_categorys','vehicle_categorys.id','=','trip_details.vehicle_category_id')
	        					->where('trip_details.customer_id',$customer_id)
	        					->where('trip_details.status','ongoing')
	        					->orderBy('trip_details.created_at','DESC')
	        					->get()
	        					->first();
	        		ResponseMessage::success("Ongoing trip detail",$ongoing);
	        	} else{
	        		ResponseMessage::error("User not exits.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);	
    	}
    }
    public function cancel_trip(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
    			'trip_id' => 'required',
    			'reason' => 'required',
    			'latitude' => 'required',
    			'longitude' => 'required',
			];
			$customeMessage = [
				'customer_id' => 'Please sent customer id.',
				'trip_id.required' => 'Please sent trip id.',
				'reason.required' => 'Please sent reason.',
				'latitude.required' => 'Please sent latitude.',
				'longitude.required' => 'Please sent longitude.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$customer_id = trim(strip_tags($request->customer_id));
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$reason = trim(strip_tags($request->reason));
	        	$latitude = trim(strip_tags($request->latitude));
	        	$longitude = trim(strip_tags($request->longitude));
	        	$status = "cancel";
	        	$type = "customer";
	        	$updated_at = Date('Y-m-d H:i:s');

	        	if(Tripdetail::where('key',$trip_id)->where('status','!=',$status)->exists()){
	        		$tripcancel = New Tripcancel;
	        		$tripcancel->trip_id = $trip_id;
	        		$tripcancel->type = $type;
	        		$tripcancel->user_id = $customer_id;
	        		$tripcancel->reason = $reason;
	        		$tripcancel->latitude = $latitude;
	        		$tripcancel->longitude = $longitude;
	        		if($tripcancel->save()){
		        	 	$tripdetail = Tripdetail::find($trip_id);
		        	 	$tripdetail->status = $status;
		        	 	$tripdetail->updated_at = $updated_at;
		        	 	if($tripdetail->save()){
	        				// Availabledriver::where('driver_id',$tripdetail->driver_id)->update(['status','on']);
		        	 		Notificationlog::create("customer",$customer_id,"You cancel trip for".$tripdetail->drop_address,"yes");
		        	 		Notificationlog::create("driver",$tripdetail->driver_id,"Customer cancel trip for ".$tripdetail->drop_address,"yes");
		        	 		$response = Tripdetail::select('driver_id','key')->where('key',$trip_id)->get()->first();
		        	 		ResponseMessage::success("Trip cancel.",["driver_id"=>$response->driver_id,"trip_id"=>$response->key]);
		        	 	} else{
		        	 		ResponseMessage::error("Something went wrong.");
		        	 	}
	        		} else{
	        			ResponseMessage::error("Something went wrong.");
	        		}
	        	} else{
	        		ResponseMessage::error("Trip detail not exits.");
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }
}
