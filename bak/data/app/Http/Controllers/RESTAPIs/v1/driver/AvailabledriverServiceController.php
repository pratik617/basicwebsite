<?php

namespace App\Http\Controllers\RESTAPIs\v1\driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Driver\Driver;
use App\Model\Driver\Availabledriver;
use App\Helper\ResponseMessage;
use App\Helper\Exceptions;
use Validator;
use Auth;
use DB;

class AvailabledriverServiceController extends Controller
{
	public function available_drive(Request $request)
	{
	    try {
	    	$rules = [
                'driver_id' => 'required',
                'status' => 'required|in:off,on',
                'latitude' => 'required',
                'longitude' => 'required',
                'vehicle_type' => 'required',
                'vehicle_category' => 'required',
            ];
            $customeMessage = [
            	'driver_id.required' => 'Please enter driver id.',
            	'status.required' => 'Please select status on or off',
            	'latitude.required' => 'Please enter latitude.',
            	'longitude.required' => 'Please enter longitude.',
            	'vehicle_type.required' => 'Please enter vehicle type.',
            	'vehicle_category.required' => 'Please enter vehicle category.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$driver_id = trim(strip_tags($request->driver_id));
	        	$status = trim(strip_tags($request->status));
	    		$latitude = trim(strip_tags($request->latitude));
	    		$longitude = trim(strip_tags($request->longitude));
	    		$vehicle_type = trim(strip_tags($request->vehicle_type));
	    		$vehicle_category = trim(strip_tags($request->vehicle_category));

	    		if($status=="on"){
	    			if(Availabledriver::where('driver_id',$driver_id)->exists()){
	    				Availabledriver::where('driver_id',$driver_id)
			    							->update([
								    			'latitude' => $latitude,
								    			'longitude' => $longitude,
			    								'vehicle_type' => $vehicle_type,
			    								'vehicle_category' => $vehicle_category,
			    								'status'=>$status
						    				]);
		    			ResponseMessage::success("Driver status on.");
	    			} else{
		    			$availabledriver = New Availabledriver;
		    			$availabledriver->driver_id = $driver_id;
		    			$availabledriver->latitude = $latitude;
		    			$availabledriver->longitude = $longitude;
		    			$availabledriver->vehicle_type = $vehicle_type;
		    			$availabledriver->vehicle_category = $vehicle_category;
		    			$availabledriver->status = $status;
		    			$availabledriver->save();
		    			ResponseMessage::success("Driver status on.");
	    			}
	    		} elseif($status=="off"){
	    			if(Availabledriver::where('driver_id',$driver_id)->exists()){
	    				Availabledriver::where('driver_id',$driver_id)->update([
								    			'latitude' => $latitude,
								    			'longitude' => $longitude,
			    								'vehicle_type' => $vehicle_type,
			    								'vehicle_category' => $vehicle_category,
			    								'status'=>$status
						    				]);
	    			}
	    			ResponseMessage::success("Driver status off.");
	    		}
	        }
	    } catch (Exception $e) {
	    	Exceptions::exception($e);
	    }
	}
}
