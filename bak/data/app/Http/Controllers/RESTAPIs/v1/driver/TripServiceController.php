<?php

namespace App\Http\Controllers\RESTAPIs\v1\driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Exceptions;
use App\Helper\ResponseMessage;
use App\Model\Customer\Tripdetail;
use App\Model\Customer\Retaindetail;
use App\Model\Customer\Friendsdetail;
use App\Model\Driver\Availabledriver;
use App\Model\Notificationlog;
use App\Model\Driver\Driver;
use App\Model\Driver\Tripdriverslog;
use App\Model\Vehicletype;
use App\Model\Vehiclecategory;
use App\Model\Tripcancel;
use Validator;
use Auth;
use DB;
use File;
use Response;

class TripServiceController extends Controller
{
    /**
       * 
       * Driver Past Trip Details
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
    			'driver_id' => 'required',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	if(Driver::where('id',$driver_id)->exists()){
					$tripdetails = Tripdetail::select([
										'trip_details.key',
										'trip_details.pickup_address',
										'trip_details.drop_address',
										'trip_details.fare_distance',
										'trip_details.total_price',
										'trip_details.pickup_time',
										'trip_details.payment_mode',
										'trip_details.map_image',
										'trip_details.drop_time',
										'users.name as customer_name',
										'users.profile as customer_profile',
										'users.country_code',
										'users.contact_no',
										'users.rating as rate',
										'vehicle_categorys.name as vehicle_category_name'
									])
									->leftjoin('users','users.id','=','trip_details.customer_id')
									->leftjoin('vehicle_categorys','vehicle_categorys.id','=','trip_details.vehicle_category_id')
									->where('trip_details.driver_id',$driver_id)
									->where('trip_details.status','complete')
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

    public function trip_decline_driver(Request $request)
    {
    	try {
    		$rules = [
    			'trip_id' => 'required',
    			'driver_id' => 'required',
    			'type' => 'required|in:missed,decline'
			];
			$customeMessage = [
				'trip_id.required' => 'Please sent trip id.',
				'driver_id.required' => 'Please sent driver id.',
				'type.required'	=> 'Please sent type.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$type = trim(strip_tags($request->type));
	        	$radius = config('constants.near.radius');
	        	$pickup_latitude = "";
	        	$pickup_longitude = "";
	        	$vehicle_type_id = "";
	        	$vehicle_category_id = "";
	        	$driver = "";
	        	/* CHECK DRIVER EXISTS OR NOT */
	        	if(Driver::where('id',$driver_id)->exists()){
	        		$driver_info = Driver::where('id',$driver_id)->get()->first();
	        		/*CHECK TRIP DETAILS */
	        		if(Tripdetail::where('key',$trip_id)->where('status','finding')->exists()){
	        			/* DRIVER DECLINE TRIP INSERT */
	        			if(!Tripdriverslog::where('trip_id',$trip_id)->where('driver_id',$driver_id)->exists()){
		        			$tripdriverslog = New Tripdriverslog;
		        			$tripdriverslog->trip_id = $trip_id;
		        			$tripdriverslog->driver_id = $driver_id;
		        			$tripdriverslog->type = $type;
		        			$tripdriverslog->save();
	        			}
	        			$already_declined_driver = Tripdriverslog::select('driver_id')->where('trip_id',$trip_id)->get()->toArray();
	        			$tripdetails = Tripdetail::where('key',$trip_id)->get()->first();	
	        			$pickup_latitude = $tripdetails->pickup_latitude;
	        			$pickup_longitude = $tripdetails->pickup_longitude;
	        			$vehicle_type_id = $tripdetails->vehicle_type_id;
	        			$vehicle_category_id = $tripdetails->vehicle_category_id;
	        			Notificationlog::create("customer",$tripdetails->customer_id,$driver_info->name." decline your request.","yes");
	        			Notificationlog::create("driver",$tripdetails->driver_id,"You decline request for ".$tripdetails->drop_address." trip.","yes");
	        			/*FIND */
	        			$nearDriversType = DB::table('available_drivers')
	        							->where('status','on')
	        							->whereNotIn('driver_id',$already_declined_driver)
										->select("driver_id",DB::raw( "(6371 * acos( cos( radians($pickup_latitude) ) * cos( radians( latitude ) )  *  cos( radians( longitude ) - radians($pickup_longitude) ) + sin( radians($pickup_latitude) ) * sin(  radians( latitude ) ) ) ) as distance") )
										->where('vehicle_type',$vehicle_type_id)
										->where('vehicle_category',$vehicle_category_id)
			                     		->orderby('vehicle_type','ASC')
			                     		->orderby('vehicle_category','ASC')
			                     		->orderby('distance','ASC')
			                     		->get()
			                     		->first();
			            if(count($nearDriversType)!=0){
			            	$driver = [
			            		"driver_id"=>$nearDriversType->driver_id,
			            		"customer_id"=>$tripdetails->customer_id,
			            		"trip_id" => $trip_id
			            	];
	        				ResponseMessage::success("success",$driver);
			            } else{
			            	$tripdetails = Tripdetail::where('key',$trip_id)->get()->first();	
			            	ResponseMessage::error("Near driver not found.",["trip_id"=>$trip_id,"customer_id"=>$tripdetails->customer_id]);	
			            }
	        		} else{
	        			ResponseMessage::error("Trip details not found");	
	        		}
	        	} else{
	        		ResponseMessage::error("Driver not exits");	
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function missed_trip_list(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	if(Driver::where('id',$driver_id)->exists()){
	        		$missed_trip = Tripdriverslog::select('trip_drivers_log.created_at','trip_details.pickup_address','trip_details.drop_address')
	        						->leftjoin('trip_details','trip_details.key','=','trip_drivers_log.trip_id')
        							->where('trip_drivers_log.type','missed')
        							->where('trip_drivers_log.driver_id',$driver_id)
        							->orderby('trip_details.created_at','DESC')
	        						->get()
	        						->toArray();
	        		ResponseMessage::success("suucess",$missed_trip);
	        	} else{
	        		ResponseMessage::error("Driver not exits");	
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function pickup_customer(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'trip_id' => 'required',
    			'latitude' => 'required',
    			'longitude' => 'required',
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'trip_id.required' => 'Please sent trip id.',
				'latitude.required' => 'Please sent latitude.',
				'longitude.required' => 'Please sent longitude.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$latitude = trim(strip_tags($request->latitude));
	        	$longitude = trim(strip_tags($request->longitude));
	        	$status = "ongoing";
	        	if(Tripdetail::where('key',$trip_id)->where('driver_id',$driver_id)->exists()){
	        		$driver_info = Driver::where('id',$driver_id)->get()->first();
	        		$tripdetail = Tripdetail::find($trip_id);
	        		$tripdetail->pickup_latitude = $latitude;
	        		$tripdetail->pickup_longitude = $longitude;
	        		$tripdetail->status = $status;
	        		$tripdetail->pickup_time = date('Y-m-d H:i:s');
	        		if($tripdetail->save()){
	        			Notificationlog::create("customer",$tripdetail->customer_id, $driver_info->name." driver pickup you for your ".$tripdetails->drop_address." trip.","yes");
	        			$response = [
	        				'drop_address' => $tripdetail->drop_address,
	        				'drop_latitude' => $tripdetail->drop_latitude,
	        				'drop_longitude' => $tripdetail->drop_longitude,
	        			];
	        			ResponseMessage::success("suucess",$response);
	        		} else{
	        			ResponseMessage::error("Something went wrong.");	
	        		}
	        	} else{
	        		ResponseMessage::error("Tripdetail or driver not found.");	
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function trip_complate(Request $request)
    {
    	try {
    		$rules = [
    			'trip_id' => 'required',
    			'latitude' => 'required',
    			'longitude' => 'required',
    			'image' => 'image', //required
			];
			$customeMessage = [
				'trip_id.required' => 'Please sent trip id.',
				'latitude.required' => 'Please sent latitude.',
				'longitude.required' => 'Please sent longitude.',
				'image.image' => 'Image invalid.'
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$latitude = trim(strip_tags($request->latitude));
	        	$longitude = trim(strip_tags($request->longitude));
	        	if($request->hasFile('image')) {
	        		$time = md5(time());
	        		$file = $request->image;
	        		$extension = $file->getClientOriginalExtension();
	        		$map_image = $time.'.'.$file->getClientOriginalExtension();
	        		$file->move(public_path('trip/'.$trip_id.'/images/'),$map_image);
	        		$map_image_path = 'trip/'.$trip_id."/images/".$map_image;
	        	} else{
	        		$map_image_path = "";
	        	}

	        	$status = 'complete';
	        	$datetime = date('Y-m-d H:i:s');

	        	if( Tripdetail::where('key',$trip_id)->exists() ){
	        		$trip_details = Tripdetail::findOrFail($trip_id);
	        		if($trip_details){
	        			$trip_details->status = $status;
	        			$trip_details->drop_time = $datetime;
	        			$trip_details->drop_latitude = $latitude;
	        			$trip_details->drop_longitude = $longitude;
	        			$trip_details->map_image = $map_image_path;
	        			if($trip_details->save()){
	        				$driver_info = Driver::where('id',$trip_details->driver_id)->get()->first();

	        				Notificationlog::create("customer",$trip_details->customer_id, $driver_info->name." complate ".$trip_details->drop_address." trip.","yes");

	        				$response = Tripdetail::select('trip_details.total_price','countrys.currency_sign')
	        								->leftjoin('countrys','countrys.currency_code','=','trip_details.currency_code')
	        								->where('trip_details.key',$trip_id)
	        								->get()
	        								->first();
	        				ResponseMessage::success("Trip complete",$response);
	        			} else{
	        				ResponseMessage::error("Something went wrong.");
	        			}
	        		} else{
	        			ResponseMessage::error("Trip detail not found.");
	        		}
	        	} else{
	        		ResponseMessage::error("Trip detail not found.");	
	        	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function report(Request $request)
    {
    	try {
    		$rules = [
    			'driver_id' => 'required',
    			'fromdate' => 'required',
    			'todate' => 'required'
			];
			$customeMessage = [
				'driver_id.required' => 'Please sent driver id.',
				'fromdate.required' => 'Please sent from date id.',
				'todate.required' => 'Please sent to date id.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	/*VARIABLE*/
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$fromdate = trim(strip_tags($request->fromdate));
	        	$todate = trim(strip_tags($request->todate));

	        	if(Driver::where('id',$driver_id)->exists()){
	        		$response = Tripdetail::select('trip_details.pickup_address','trip_details.drop_address','trip_details.drop_time','trip_details.payment_mode','trip_details.total_price','countrys.currency_sign','trip_details.status')
	        					->where('trip_details.driver_id',$driver_id)->where('trip_details.status','complete')
	        					->whereBetween(DB::raw('DATE_FORMAT(trip_details.drop_time, "%Y-%m-%d")'),[$fromdate,$todate])
	        					->leftjoin('countrys','countrys.currency_code','=','trip_details.currency_code')
	        					->orderby('trip_details.created_at','DESC')
        						->get();
	        		ResponseMessage::success("Trip Report",$response);
	        	} else{
	        		ResponseMessage::error("Driver not found.");
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
    			'driver_id' => 'required',
    			'trip_id' => 'required',
    			'reason' => 'required',
    			'latitude' => 'required',
    			'longitude' => 'required',
			];
			$customeMessage = [
				'driver_id' => 'Please sent driver id.',
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
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$trip_id = trim(strip_tags($request->trip_id));
	        	$reason = trim(strip_tags($request->reason));
	        	$latitude = trim(strip_tags($request->latitude));
	        	$longitude = trim(strip_tags($request->longitude));
	        	$status = "cancel";
	        	$type = "driver";
	        	$updated_at = Date('Y-m-d H:i:s');

	        	if(Tripdetail::where('key',$trip_id)->where('status','!=',$status)->exists()){
	        		$tripcancel = New Tripcancel;
	        		$tripcancel->trip_id = $trip_id;
	        		$tripcancel->type = $type;
	        		$tripcancel->user_id = $driver_id;
	        		$tripcancel->reason = $reason;
	        		$tripcancel->latitude = $latitude;
	        		$tripcancel->longitude = $longitude;
	        		if($tripcancel->save()){
		        	 	$tripdetail = Tripdetail::find($trip_id);
		        	 	$tripdetail->status = $status;
		        	 	$tripdetail->updated_at = $updated_at;
		        	 	if($tripdetail->save()){
		        	 		Notificationlog::create("customer",$tripdetail->customer_id,"Driver cancel trip for ".$tripdetail->drop_address,"yes");
		        	 		Notificationlog::create("driver",$tripdetail->driver_id,"You cancel trip for ".$tripdetail->drop_address,"yes");
		        	 		// $response = Tripdetail::select('customer_id','key')->where('key',$trip_id)->get()->first();
		        	 		$driver_info = Driver::where('id',$tripdetail->driver_id)->get()->first();
		        			/* DRIVER DECLINE TRIP INSERT */
		        			if(!Tripdriverslog::where('trip_id',$trip_id)->where('driver_id',$tripdetail->driver_id)->exists()){
			        			$tripdriverslog = New Tripdriverslog;
			        			$tripdriverslog->trip_id = $trip_id;
			        			$tripdriverslog->driver_id = $tripdetail->driver_id;
			        			$tripdriverslog->type = "cancel";
			        			$tripdriverslog->save();
			        			// Availabledriver::where('driver_id',$driver_id)->update(['status','on']);
		        			}
		        			$already_declined_driver = Tripdriverslog::select('driver_id')->where('trip_id',$trip_id)->get()->toArray();

		        			$tripdetails = Tripdetail::where('key',$trip_id)->get()->first();	
		        			$pickup_latitude = $tripdetails->pickup_latitude;
		        			$pickup_longitude = $tripdetails->pickup_longitude;
		        			$vehicle_type_id = $tripdetails->vehicle_type_id;
		        			$vehicle_category_id = $tripdetails->vehicle_category_id;
		        			$radius = config('constants.near.radius');

							/*FIND */
		        			$nearDriversType = DB::table('available_drivers')
		        							->where('status','on')
		        							->whereNotIn('driver_id',$already_declined_driver)
											->select("driver_id",DB::raw( "(6371 * acos( cos( radians($pickup_latitude) ) * cos( radians( latitude ) )  *  cos( radians( longitude ) - radians($pickup_longitude) ) + sin( radians($pickup_latitude) ) * sin(  radians( latitude ) ) ) ) < $radius") )
											->where('vehicle_type',$vehicle_type_id)
											->where('vehicle_category',$vehicle_category_id)
				                     		->orderby('vehicle_type','ASC')
				                     		->orderby('vehicle_category','ASC')
				                     		->get()
				                     		->first();

				            if(count($nearDriversType)!=0){
				            	$driver = [
				            		"driver_id"=>$nearDriversType->driver_id,
				            		"customer_id"=>$tripdetails->customer_id,
				            		"trip_id" => $trip_id
				            	];
		        				ResponseMessage::success("success",$driver);
				            } else{
				            	$tripdetails = Tripdetail::where('key',$trip_id)->get()->first();	
				            	ResponseMessage::error("Near driver not found.",["trip_id"=>$trip_id,"customer_id"=>$tripdetails->customer_id]);	
				            }
		        	 		// ResponseMessage::success("Trip cancel.",["customer_id"=>$response->customer_id,"trip_id"=>$response->key]);
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

    public function getMapImage($trip_id,$map_image)
    {
    	try {
    		$file = File::get(public_path()."/trip/".$trip_id."/images/".$map_image);
			$response = Response::make($file, 200);
			$response->header('Content-Type', 'application/image');
			return $response;
    	} catch (Exception $e) {
    		Exceptions::exception($e);		
    	}
    }
}