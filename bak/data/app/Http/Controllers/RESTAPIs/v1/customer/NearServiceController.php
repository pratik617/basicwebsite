<?php

namespace App\Http\Controllers\RESTAPIs\v1\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\Exceptions;
use App\Helper\ResponseMessage;
use App\Helper\TimeHelper;
use App\Model\Driver\Availabledriver;
use App\Helper\RandomHelper;
use App\Model\Customer\Tripdetail;
use App\Model\Customer\Retaindetail;
use App\Model\Notificationlog;
use App\Model\Customer\Friendsdetail;
use App\Model\Customer\Tripcharge;
use App\Helper\DistanceHelper;
use App\Model\Vehicletype;
use App\Model\Vehiclecategory;
use App\Model\Taxe;
use App\Model\Driver\Driver;
use App\Model\Rideprice;
use App\Model\Country;
use App\User;
use Auth;
use Validator;
use DB;

class NearServiceController extends Controller
{

    public function find_near_me(Request $request)
    {
    	try {
    		$rules = [
    			'latitude' => 'required',
    			'longitude' => 'required',
			];
			$customeMessage = [
				'latitude.required' => 'Please sent latitude.',
				'longitude.required' => 'Please sent longitude.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$latitude = trim(strip_tags($request->latitude));
	        	$longitude = trim(strip_tags($request->longitude));
	        	$radius = config('constants.near.radius');
				$userNearestList = Availabledriver::select("latitude","longitude")
							->whereRaw( DB::raw( "(6371 * acos( cos( radians($latitude) ) * cos( radians( latitude ) )  *  cos( radians( longitude ) - radians($longitude) ) + sin( radians($latitude) ) * sin(  radians( latitude ) ) ) ) < $radius "))
							->take(config('constants.near.count'))->get();
				ResponseMessage::success("Near Cab",$userNearestList);
	        }	
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    public function find_nearby_vehicle_type(Request $request)
    {
    	try {
			$rules = [
    			'pickup_latitude' => 'required',
    			'pickup_longitude' => 'required',
    			'drop_latitude' => 'required',
    			'drop_longitude' => 'required',
    			'country_iso' => 'required',
    			'current_time' => '',
    			'with_friend' => '',
    			'friend_latitude' => '',
    			'friend_longitude' => '',
    			'retain' => '',
    			'retain_hours' => '',
			];
			$customeMessage = [
				'pickup_latitude.required' => 'Please sent pickup latitude.',
				'pickup_longitude.required' => 'Please sent pickup longitude.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$pickup_latitude = trim(strip_tags($request->pickup_latitude));
	        	$pickup_longitude = trim(strip_tags($request->pickup_longitude));
	        	$drop_latitude = trim(strip_tags($request->drop_latitude));
	        	$drop_longitude = trim(strip_tags($request->drop_longitude));
	        	$country_iso = trim(strip_tags($request->country_iso));
	        	$current_time = trim(strip_tags($request->current_time));

	        	$with_friend = trim(strip_tags($request->with_friend))?trim(strip_tags($request->with_friend)):'no';
	        	$friend_latitude = trim(strip_tags($request->friend_latitude));
	        	$friend_longitude = trim(strip_tags($request->friend_longitude));

	        	$retain = trim(strip_tags($request->retain))?trim(strip_tags($request->retain)):'no';
	        	$retain_hours = trim(strip_tags($request->retain_hours));

	        	if(Country::where('code_2',$country_iso)->exists()){
		        	$country_setting = Country::where('code_2',$country_iso)->get()->first();
		        	$taxes = Taxe::getTaxes($country_setting->id);
		        	$radius = config('constants.near.radius');

		        	$userNearestList = Availabledriver::select("vehicle_type","vehicle_category")
	        					->where('status','on')
								->whereRaw( DB::raw( "(6371 * acos( cos( radians($pickup_latitude) ) * cos( radians( latitude ) )  * 
			                          cos( radians( longitude ) - radians($pickup_longitude) ) + sin( radians($pickup_latitude) ) * sin( 
			                          radians( latitude ) ) ) ) < $radius ")
			                     )->groupby('vehicle_type','vehicle_category')->orderby('vehicle_type')->get();
					
					$last_id = "";	
					$types = [];
					$result = [];
					foreach ($userNearestList as $nearDriver) {
						if($last_id=="" || $last_id!=$nearDriver->vehicle_type){
							$last_id=$nearDriver->vehicle_type;
							$types[] = Vehicletype::select('id','name')
										->where('status','active')
										->where('id',$nearDriver->vehicle_type)
										->get()
										->first();
						}
					}
					$temp_type = [];
					$temp_cate = [];
					foreach ($types as $type) {
						$nearDriversType = DB::table('available_drivers')
										->where('status','on')
										->select("driver_id","vehicle_type","vehicle_category","latitude","longitude",DB::raw( "(6371 * acos( cos( radians($pickup_latitude) ) * cos( radians( latitude ) )  *  cos( radians( longitude ) - radians($pickup_longitude) ) + sin( radians($pickup_latitude) ) * sin(  radians( latitude ) ) ) ) as distance") )
										->where('vehicle_type',$type->id)
			                     		->orderby('vehicle_type','ASC')
			                     		->orderby('vehicle_category','ASC')
			                     		->orderby('distance','ASC')
			                     		->get();

			            $temp_cate2 = [];
			            $vehicle_category_id = "";
			            foreach ($nearDriversType as $nearDriver) {
			            	if($vehicle_category_id=="" || $vehicle_category_id!=$nearDriver->vehicle_category){
			            		$vehicle_category_id=$nearDriver->vehicle_category;
			            		$rideprice = Rideprice::where('vehicle_type_id',$nearDriver->vehicle_type)->where('vehicle_catehory_id',$nearDriver->vehicle_category)->where('unit',$country_setting->unit)->get()->first();
			            		$category_info = Vehiclecategory::where("vehicle_type_id",$nearDriver->vehicle_type)->where("id",$nearDriver->vehicle_category)->get()->first();
			            		if(!isset($category_info)){
			            			dd($nearDriver);
			            		}
			            		$driver_current_latitude = $nearDriver->latitude;
			            		$driver_current_longitude = $nearDriver->longitude;
			            		$driver_distance = 0;
								$driver_duration = 0;
								$fare_distance = 0;
								$fare_duration = 0;
								$fare_price = 0;

			            		$driver_info = DistanceHelper::drivingDistance($driver_current_latitude,$driver_current_longitude,$pickup_latitude,$pickup_longitude,$country_setting->unit);

			            		if(isset($driver_info["routes"])){
						        	foreach ($driver_info["routes"] as $key => $value) {
						        		foreach ($value["legs"] as $lkey => $lvalue) {
						        			$driver_distance = $lvalue["distance"]["text"];
						        			$driver_duration = $lvalue["duration"]["text"];			        			
						        		}
						        	}
					        		$driver_estimate_arrivel_time = TimeHelper::google_plus_time($driver_duration,"","Y-m-d H:i:s");
						        } else{
									ResponseMessage::error("Route not found");
						        }

						        $customer_info = ""; 
						        if($with_friend=="no"){
						        	$customer_info = DistanceHelper::drivingDistance($pickup_latitude,$pickup_longitude,$drop_latitude,$drop_longitude,$country_setting->unit);
						        } else{
						        	$customer_info = DistanceHelper::drivingDistance($pickup_latitude,$pickup_longitude,$drop_latitude,$drop_longitude,$country_setting->unit,$friend_latitude,$friend_longitude);
						        }
						        if(isset($customer_info["routes"])){
						        	foreach ($customer_info["routes"] as $key => $value) {
						        		foreach ($value["legs"] as $lkey => $lvalue) {
						        			$fare_distance = $lvalue["distance"]["text"];
						        			$fare_duration = $lvalue["duration"]["text"];			        			
						        		}
						        	}
					        		$fare_estimate_end_time = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time,"Y-m-d H:i:s");
						        } else{
									ResponseMessage::error("Route not found");						        	
						        }
						        $fare_price = ($rideprice->price * explode(" ",$fare_distance)[0]);
								$tax_price = 0;
								if(isset($taxes)!="notax"){
									foreach ($taxes as $tax) {
										$tax_price = $tax_price + ($fare_price * $tax->percent/100);
									}
								}
								if($retain=="yes"){
									$retain_charge = (($retain_hours*60) * $rideprice->perminute);
									$fare_price = ($fare_price + $retain_charge);
								}
								$total_fare_price = $fare_price + $tax_price;
								$driver_estimate_arrivel_time_local = TimeHelper::google_plus_time($driver_duration,$current_time,"Y-m-d H:i:s");
								$fare_estimate_end_time_local = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time_local,"g:ia");

			            		$nearDetails = [
			            			"total_fare_price" => number_format((float)$total_fare_price, 2, '.', '') ,
			            			"fare_estimate_end_time" => $fare_estimate_end_time_local,
			            			"fare_estimate_end_time_utc" => $fare_estimate_end_time,
			            			"currency_sign" => $country_setting->currency_sign,
			            			"category_name" => $category_info->name,
			            			"driver_id" => $nearDriver->driver_id,
			            			"vehicle_type" => $nearDriver->vehicle_type,
			            			"vehicle_category" => $nearDriver->vehicle_category,
			            			"vehicle_person_capacity" => $category_info->min_person_capacity."-".$category_info->max_person_capacity,
			            			"fare_distance" => explode(" ", $fare_distance)[0],
			            			"fare_unit" => explode(" ", $fare_distance)[1],
			            			"fare_duration"=>$fare_duration,
			            			"driver_pickup_time"=>$driver_duration,
			            		];
			            		array_push($temp_cate2,$nearDetails);
			            	}
			            }
			            $temp_cate[] = ["id"=>$type->id,"name"=>$type->name,"category"=>$temp_cate2];
					}
		           	ResponseMessage::success("success",$temp_cate);
	           	} else{
	           		ResponseMessage::error("Country not found");
	           	}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);	
    	}
    }
    
    public function book_cab_cust(Request $request)
    {
    	try {
    		$rules = [
    			'customer_id' => 'required',
                'driver_id' => 'required',
                
                'pickup_address' => "",
                'pickup_latitude' => "required",
                'pickup_longitude' => "required",
                
                'drop_address' => "",
                'drop_latitude' => "required",
                'drop_longitude' => "required",
                
                'vehicle_type_id' => "required",
                'vehicle_category_id' => "required",

                'fare_distance' => "required",
                'fare_unit' => "required",
                'fare_duration' => "required",
                'driver_pickup_time' => '',
                
                'country_iso' => "required",

                'retain' => "required|in:yes,no",
                'retain_hours' => "",

                'with_friend' => "required|in:yes,no",
                'friend_address' => "",
                'friend_latitude' => "",
                'friend_longitude' => "",

                'promo_code' => "",
                'payment_mode' => "",
                'current_time' => "",                
            ];
            $customeMessage = [
            	'customer_id' => 'Please enter customer id.',
            	'driver_id.required' => 'Please enter driver id.',
            	'pickup_latitude.required' => 'Please select pickup location.',
            	'pickup_longitude.required' => 'Please select pickup location.',
            	'drop_latitude.required' => 'Please select drop location.',
            	'drop_longitude.required' => 'Please select drop location.',
            	'vehicle_type_id.required' => 'Please select vehicle type.',
            	'vehicle_category_id.required' => 'Please select vehicle catehory.',
            	'fare_distance.required' => 'Please sent fare distance.',
            	'fare_unit.required' => 'Please sent fare unit.',
            	'fare_duration.required' => 'Please sent fare duration.',
            	'driver_pickup_time.required' => 'Please sent driver pickup time.',
            	'country_iso.required' => 'Please sent country iso.',
            	'retain.required' => 'Please select retain or not.',
            	'retain.in' => 'Please select retain or not.',
            	'with_friend.required' => 'Please select travel with friend or not.',
            	'with_friend.in' => 'Please select travel with friend or not.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	    		$trip_id = RandomHelper::tripId();
	    		$customer_id = strip_tags(trim($request->customer_id));
				$driver_id = strip_tags(trim($request->driver_id));
				$pickup_address = strip_tags(trim($request->pickup_address))?strip_tags(trim($request->pickup_address)):'';
				$pickup_latitude = strip_tags(trim($request->pickup_latitude));
				$pickup_longitude = strip_tags(trim($request->pickup_longitude));

				$drop_address = strip_tags(trim($request->drop_address))?strip_tags(trim($request->drop_address)):'';
				$drop_latitude = strip_tags(trim($request->drop_latitude));
				$drop_longitude = strip_tags(trim($request->drop_longitude));

				$retain = strip_tags(trim($request->retain))?strip_tags(trim($request->retain)):'no';
				$retain_hours = strip_tags(trim($request->retain_hours))?strip_tags(trim($request->retain_hours)):0;
				
				$with_friend = strip_tags(trim($request->with_friend))?strip_tags(trim($request->with_friend)):'no';
				$friend_address = strip_tags(trim($request->friend_address))?strip_tags(trim($request->friend_address)):'';
				$friend_latitude = strip_tags(trim($request->friend_latitude));
				$friend_longitude = strip_tags(trim($request->friend_longitude));

				$fare_distance = strip_tags(trim($request->fare_distance))?strip_tags(trim($request->fare_distance)):'';
				$fare_duration = strip_tags(trim($request->fare_duration));

				$pickup_time = strip_tags(trim($request->pickup_time))?strip_tags(trim($request->pickup_time)):'';
				$drop_time = strip_tags(trim($request->drop_time))?strip_tags(trim($request->drop_time)):'';
				
				$payment_mode = strip_tags(trim($request->payment_mode))?strip_tags(trim($request->payment_mode)):'';
				$promo_code = strip_tags(trim($request->promo_code))?strip_tags(trim($request->promo_code)):'';
				
				$vehicle_type_id = strip_tags(trim($request->vehicle_type_id));
				$vehicle_category_id = strip_tags(trim($request->vehicle_category_id));
				
				$country_iso = strip_tags(trim($request->country_iso));
				$fare_estimate_end_time = strip_tags(trim($request->fare_estimate_end_time));
				
				$current_time = trim(strip_tags($request->current_time));
				$driver_pickup_time = trim(strip_tags($request->driver_pickup_time));

				$retain_time = null;
				$retain_pickup_time = null;
				$retain_drop_time = null;

				$country_setting = Country::where('code_2',$country_iso)->get()->first();

				if(Availabledriver::where('driver_id',$driver_id)->exists()){
					$availabledriver = Availabledriver::where('driver_id',$driver_id)->get()->first();
					$taxes = Taxe::getTaxes($country_setting->id);
					$customer_info = ""; 
				    if($with_friend=="no"){
			        	$customer_info = DistanceHelper::drivingDistance($pickup_latitude,$pickup_longitude,$drop_latitude,$drop_longitude,$country_setting->unit);
			        } else{
			        	$customer_info = DistanceHelper::drivingDistance($pickup_latitude,$pickup_longitude,$drop_latitude,$drop_longitude,$country_setting->unit,$friend_latitude,$friend_longitude);
			        }
			        if(isset($customer_info["routes"])){
			        	foreach ($customer_info["routes"] as $key => $value) {
			        		foreach ($value["legs"] as $lkey => $lvalue) {
			        			$fare_distance = $lvalue["distance"]["text"];
			        			$fare_duration = $lvalue["duration"]["text"];			        			
			        		}
			        	}
			        	$driver_estimate_arrivel_time = TimeHelper::google_plus_time($driver_pickup_time,"","Y-m-d H:i:s");
		        		$fare_estimate_end_time = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time,"Y-m-d H:i:s");
			        } else{
						ResponseMessage::error("Route not found");
			        }
					$rideprice = Rideprice::where('vehicle_type_id',$vehicle_type_id)->where('vehicle_catehory_id',$vehicle_category_id)->where('unit',$country_setting->unit)->get()->first();
					$fare_price = (explode(" ", $fare_distance)[0] * $rideprice->price);

					$fare_charge = $this->trip_charge($trip_id,"trip",number_format((float)$fare_price, 2, '.', ''),"Fare Amount","plus");
					if($fare_charge!=true){
						ResponseMessage::error("Trip charges not add");
					}

					$tax_price = 0;
					if($taxes!="notax"){
						foreach ($taxes as $tax) {
							$tax_price = $tax_price + ($fare_price * $tax->percent/100);
							$tax_amount = ($fare_price * $tax->percent/100);
							$fare_tax_charge = $this->trip_charge($trip_id,"taxes",number_format((float)$tax_amount, 2, '.', ''),"TAX ".$tax->tax_name,"plus");
							if($fare_tax_charge!=true){
								ResponseMessage::error("Trip charges not add");
							}
						}
					}
					if($retain=="yes"){
						$retain_charge = (($retain_hours*60) * $rideprice->perminute);
						$fare_price = ($fare_price + $retain_charge);

						$fare_retain_charge = $this->trip_charge($trip_id,"retain",number_format((float)$retain_charge, 2, '.', ''),"Retain charge ","plus");
						if($fare_retain_charge!=true){
							ResponseMessage::error("Trip charges not add");
						}
					}
					$total_fare_price = $fare_price + $tax_price;
					$total_fare_price = number_format((float)$total_fare_price, 2, '.', '');

					$driver_pickup = TimeHelper::google_plus_time($driver_pickup_time,$current_time,"Y-m-d H:i:s");
					$driver_pickup_time_utc = TimeHelper::google_plus_time($driver_pickup_time,date('Y-m-d H:i:s'),"Y-m-d H:i:s");
					$drop_time = TimeHelper::google_plus_time($fare_duration,$driver_pickup,"Y-m-d H:i:s");
					$drop_time_utc = TimeHelper::google_plus_time($fare_duration,$driver_pickup_time_utc,"Y-m-d H:i:s");

		    		$tripdetail = New Tripdetail;
		    		$tripdetail->key = $trip_id;
					$tripdetail->customer_id = $customer_id;
					$tripdetail->driver_id = $driver_id; //$driver_id
					$tripdetail->pickup_address = $pickup_address;
					$tripdetail->pickup_latitude = $pickup_latitude;
					$tripdetail->pickup_longitude = $pickup_longitude;
					$tripdetail->drop_address = $drop_address;
					$tripdetail->drop_latitude = $drop_latitude;
					$tripdetail->drop_longitude = $drop_longitude;
					$tripdetail->status = "finding";
					$tripdetail->retain = $retain;
					if($retain=="yes"){
						$retain_time = date('Y-m-d H:i:s',strtotime("+".$retain_hours."hours"));
						$retain_drop_time = TimeHelper::google_plus_time($fare_duration,$retain_time,"Y-m-d H:i:s");

						$retaindetail = New Retaindetail;
						$retaindetail->trip_id = $trip_id;
						$retaindetail->pickup_address = $drop_address;
						$retaindetail->pickup_latitude = $drop_latitude;
						$retaindetail->pickup_longitude = $drop_longitude;
						$retaindetail->drop_address = $pickup_address;
						$retaindetail->drop_latitude = $pickup_latitude;
						$retaindetail->drop_longitude = $pickup_longitude;
						$retaindetail->pickup_time = $retain_time;
						$retaindetail->drop_time = $retain_drop_time;
						$retaindetail->retain_hours = $retain_hours;
						$retaindetail->retain_time = $retain_time;
						$retaindetail->save();
					}
					$tripdetail->travel_with_friend = $with_friend;
					if($with_friend=="yes"){
						$friendsdetail = New Friendsdetail;
						$friendsdetail->trip_id = $trip_id;
						$friendsdetail->pickup_address = $friend_address;
						$friendsdetail->pickup_latitude = $friend_latitude;
						$friendsdetail->pickup_longitude = $friend_longitude;
						$friendsdetail->pickup_time = null;
						$friendsdetail->drop_time = null;
						$friendsdetail->save();
					}
					$tripdetail->invoice = null;
					$tripdetail->fare_distance = $fare_distance;
					$tripdetail->fare_unit = $country_setting->unit;
					$tripdetail->fare_duration = $fare_duration;
					$tripdetail->currency_code = $country_setting->currency_code;
					$tripdetail->payment_id = null;
					$tripdetail->pickup_time = $driver_pickup_time_utc?$driver_pickup_time_utc:null;
					$tripdetail->drop_time = $drop_time_utc?$drop_time_utc:null;
					$tripdetail->payment_mode = $payment_mode?$payment_mode:'cash';
					$tripdetail->total_price = $total_fare_price;
					$tripdetail->promo_code = $promo_code;
					$tripdetail->vehicle_type_id = $vehicle_type_id;
					$tripdetail->vehicle_category_id = $vehicle_category_id;
					if($tripdetail->save()){ 
						$driverOff = Availabledriver::where('driver_id',$driver_id)->where("status","on")->get()->first();
						$driver_estimate_arrivel_time_local = TimeHelper::google_plus_time($driver_pickup_time,$current_time,"Y-m-d H:i:s");
						$fare_estimate_end_time_local = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time_local,"g:ia");
						if($driverOff){
							$driver = Driver::select('drivers.name','drivers.rating','drivers.country_code','drivers.contact_no','drivers.profile','drivers.vehicle_number','vehicle_categorys.name as vehicle_category_name')
										->leftjoin('vehicle_categorys','vehicle_categorys.id','=','drivers.vehicle_category')
										->where('drivers.id',$driver_id)
										->get()
										->first();
							$driver['driver_id'] = $driver_id;
							$driver['driver_latitude'] = $availabledriver->latitude;
							$driver['driver_longitude'] = $availabledriver->longitude;
							$driver['pickup_latitude'] = $pickup_latitude;
							$driver['pickup_longitude'] = $pickup_longitude;
							$driver['drop_latitude'] = $drop_latitude;
							$driver['drop_longitude'] = $drop_longitude;
							$driver['fare_estimate_end_time'] = $fare_estimate_end_time_local;
							$driver['trip_key'] = $trip_id;							
							$driver['retain'] = $retain;
							$driver['retain_hours'] = $retain_hours;
							$driver['with_friend'] = $with_friend;
							$driver['friend_latitude'] = $friend_latitude;
							$driver['friend_longitude'] = $friend_longitude;
							$driver['total_price'] = $total_fare_price;
							$driver['currency_sign'] = $country_setting->currency_sign;

		    				ResponseMessage::success("Success",$driver);
						} else{
							ResponseMessage::error("Ride not book please try again.");
						}
					} else{
						ResponseMessage::error("Ride not book please try again.");
					}
				} else{
					$data['pickup_latitude'] = $pickup_latitude;
					$data['pickup_longitude'] = $pickup_longitude;
					$data['drop_latitude'] = $drop_latitude;
					$data['drop_longitude'] = $drop_longitude;
					$data['vehicle_type_id'] = $vehicle_type_id;
					$data['vehicle_category_id'] = $vehicle_category_id;
					$data['retain'] = $retain;
					$data['retain_hours'] = $retain_hours;
					$data['with_friend'] = $with_friend;
					$data['friend_latitude'] = $friend_latitude;
					$data['friend_longitude'] = $friend_longitude;
					$data['country_iso'] = $country_iso;
					$data['current_time'] = $current_time;

					$driver = $this->nearby_vehicle_category($data);
					$drivernear = json_decode($driver);
					if($drivernear->status===1){
						$tripdetail = New Tripdetail;
			    		$tripdetail->key = $trip_id;
						$tripdetail->customer_id = $customer_id;
						$tripdetail->driver_id = null; //$drivernear->data->category[0]->driver_id
						$tripdetail->pickup_address = $drivernear->data->category[0]->start_address;
						$tripdetail->pickup_latitude = $pickup_latitude;
						$tripdetail->pickup_longitude = $pickup_longitude;
						$tripdetail->drop_address = $drivernear->data->category[0]->end_address;
						$tripdetail->drop_latitude = $drop_latitude;
						$tripdetail->drop_longitude = $drop_longitude;
						$tripdetail->status = "finding";
						$tripdetail->retain = $retain;
						if($retain=="yes"){
							$retain_time = date('Y-m-d H:i:s',strtotime("+".$retain_hours."hours"));
							$retain_drop_time = TimeHelper::google_plus_time($drivernear->data->category[0]->fare_duration,$retain_time,"Y-m-d H:i:s");
							$retaindetail = New Retaindetail;
							$retaindetail->trip_id = $trip_id;
							$retaindetail->pickup_address = $drop_address;
							$retaindetail->pickup_latitude = $drop_latitude;
							$retaindetail->pickup_longitude = $drop_longitude;
							$retaindetail->drop_address = $pickup_address;
							$retaindetail->drop_latitude = $pickup_latitude;
							$retaindetail->drop_longitude = $pickup_longitude;
							$retaindetail->pickup_time = $retain_pickup_time?$retain_pickup_time:null;
							$retaindetail->drop_time = $retain_drop_time?$retain_drop_time:null;
							$retaindetail->retain_hours = $retain_hours;
							$retaindetail->retain_time = $retain_time?$retain_time:null;
							$retaindetail->save();							
						}
						$tripdetail->travel_with_friend = $with_friend;
						if($with_friend=="yes"){
							$friendsdetail = New Friendsdetail;
							$friendsdetail->trip_id = $trip_id;
							$friendsdetail->pickup_address = $friend_address;
							$friendsdetail->pickup_latitude = $friend_latitude;
							$friendsdetail->pickup_longitude = $friend_longitude;
							$friendsdetail->pickup_time = null;
							$friendsdetail->drop_time = null;
							$friendsdetail->save();
						}
						$tripdetail->invoice = null;
						$tripdetail->fare_distance = $drivernear->data->category[0]->fare_distance;
						$tripdetail->fare_unit = $drivernear->data->category[0]->fare_unit;
						$tripdetail->fare_duration = $drivernear->data->category[0]->fare_duration;
						$tripdetail->currency_code = $drivernear->data->category[0]->currency_code;
						$tripdetail->payment_id = null;
						$tripdetail->pickup_time = $pickup_time?$pickup_time:null;
						$tripdetail->drop_time = $drop_time?$drop_time:null;
						$tripdetail->payment_mode = $payment_mode?$payment_mode:'cash';
						$tripdetail->total_price = $drivernear->data->category[0]->total_fare_price?$drivernear->data->category[0]->total_fare_price:null;
						$tripdetail->promo_code = $promo_code;
						$tripdetail->vehicle_type_id = $drivernear->data->id;
						$tripdetail->vehicle_category_id = $drivernear->data->category[0]->vehicle_category;
						if($tripdetail->save()){ 
							$driveravailable = Availabledriver::where('driver_id',$drivernear->data->category[0]->driver_id)->where('status','on')->get()->first();
							if($driveravailable){
								$driver = Driver::select('drivers.name','drivers.rating','drivers.country_code','drivers.contact_no','drivers.profile','drivers.vehicle_number','vehicle_categorys.name as vehicle_category_name')
											->leftjoin('vehicle_categorys','vehicle_categorys.id','=','drivers.vehicle_category')
											->where('drivers.id',$drivernear->data->category[0]->driver_id)
											->get()
											->first();
								$driver['driver_id'] = $drivernear->data->category[0]->driver_id;
								$driver['driver_latitude'] = $driveravailable->latitude;
								$driver['driver_longitude'] = $driveravailable->longitude;
								$driver['pickup_latitude'] = $pickup_latitude;
								$driver['pickup_longitude'] = $pickup_longitude;
								$driver['drop_latitude'] = $drop_latitude;
								$driver['drop_longitude'] = $drop_longitude;
								$driver['fare_estimate_end_time'] = $drivernear->data->category[0]->fare_estimate_end_time;
								$driver['trip_key'] = $trip_id;
								$driver['retain'] = $retain;
								$driver['retain_hours'] = $retain_hours;
								$driver['with_friend'] = $with_friend;
								$driver['friend_latitude'] = $friend_latitude;
								$driver['friend_longitude'] = $friend_longitude;	
								$driver['total_price'] = $drivernear->data->category[0]->total_fare_price;
								$driver['currency_sign'] = $country_setting->currency_sign;

			    				ResponseMessage::success("Success",$driver);
							} else{
								ResponseMessage::error("Ride not book please try again.",$request->all());
							}
						} else{
							ResponseMessage::error("Ride not book please try again.",$request->all());
						}
					} else{
						ResponseMessage::error("Driver not found please try again",[]);
					}
				}
	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);
    	}
    }

    /* -------------Search Near By Ride Internally---------------- */
    public function nearby_vehicle_category($request)
    {
    	try {
        	$pickup_latitude = trim(strip_tags($request['pickup_latitude']));
        	$pickup_longitude = trim(strip_tags($request['pickup_longitude']));
        	$drop_latitude = trim(strip_tags($request['drop_latitude']));
        	$drop_longitude = trim(strip_tags($request['drop_longitude']));
        	$country_iso = trim(strip_tags($request['country_iso']));
        	$current_time = trim(strip_tags($request['current_time']));
        	$radius = config('constants.near.radius');
        	$type_id = trim(strip_tags($request['vehicle_type_id']));
        	$category_id = trim(strip_tags($request['vehicle_category_id']));

        	$retain = trim(strip_tags($request['retain']));
        	$retain_hours = trim(strip_tags($request['retain_hours']));

    		$with_friend = trim(strip_tags($request['with_friend']));
        	$friend_latitude = trim(strip_tags($request['friend_latitude']));
        	$friend_longitude = trim(strip_tags($request['friend_longitude']));

        	if(Country::where('code_2',$country_iso)->exists()){
	        	$country_setting = Country::where('code_2',$country_iso)->get()->first();
	        	$taxes = Taxe::getTaxes($country_setting->id);

	        	$nearDriversType = DB::table('available_drivers')
									->select("driver_id","vehicle_type","vehicle_category","latitude","longitude",DB::raw( "(6371 * acos( cos( radians($pickup_latitude) ) * cos( radians( latitude ) )  *  cos( radians( longitude ) - radians($pickup_longitude) ) + sin( radians($pickup_latitude) ) * sin(  radians( latitude ) ) ) ) as distance") )
									->where("vehicle_type",$type_id)
	        						->where("vehicle_category",$category_id)
		                     		->orderby('vehicle_type','ASC')
		                     		->orderby('vehicle_category','ASC')
		                     		->orderby('distance','ASC')
		                     		->get();
				if(count($nearDriversType)){
					$temp_cate2 = [];
		            $vehicle_category_id = "";
		            foreach ($nearDriversType as $nearDriver) {
			        	$type = Vehicletype::select("name","id")->where("id",$nearDriver->vehicle_type)->get()->first();
		            	if($vehicle_category_id=="" || $vehicle_category_id!=$nearDriver->vehicle_category){
		            		$vehicle_category_id=$nearDriver->vehicle_category;

		            		$rideprice = Rideprice::where('vehicle_type_id',$nearDriver->vehicle_type)->where('vehicle_catehory_id',$nearDriver->vehicle_category)->where('unit',$country_setting->unit)->get()->first();
		            		$category_info = Vehiclecategory::where("vehicle_type_id",$nearDriver->vehicle_type)->where("id",$nearDriver->vehicle_category)->get()->first();

		            		$driver_current_latitude = $nearDriver->latitude;
		            		$driver_current_longitude = $nearDriver->longitude;
		            		$driver_distance = 0;
							$driver_duration = 0;
							$fare_distance = 0;
							$fare_duration = 0;
							$fare_price = 0;

		            		$driver_info = DistanceHelper::drivingDistance($driver_current_latitude,$driver_current_longitude,$pickup_latitude,$pickup_longitude,$country_setting->unit);

		            		if(isset($driver_info["routes"])){
					        	foreach ($driver_info["routes"] as $key => $value) {
					        		foreach ($value["legs"] as $lkey => $lvalue) {
					        			$driver_distance = $lvalue["distance"]["text"];
					        			$driver_duration = $lvalue["duration"]["text"];			        			
					        		}
					        	}
				        		$driver_estimate_arrivel_time = TimeHelper::google_plus_time($driver_duration,"","Y-m-d H:i:s");
					        } else{
								ResponseMessage::error("Route not found");
					        }

					        $customer_info = DistanceHelper::drivingDistance($pickup_latitude,$pickup_longitude,$drop_latitude,$drop_longitude,$country_setting->unit);

					        if(isset($customer_info["routes"])){
					        	foreach ($customer_info["routes"] as $key => $value) {
					        		foreach ($value["legs"] as $lkey => $lvalue) {
					        			$fare_distance = $lvalue["distance"]["text"];
					        			$fare_duration = $lvalue["duration"]["text"];
					        			$start_address = $lvalue["start_address"];
					        			$end_address = $lvalue["end_address"];
					        		}
					        	}
				        		$fare_estimate_end_time = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time,"Y-m-d H:i:s");

					        } else{
								ResponseMessage::error("Route not found");						        	
					        }

					        $fare_price = ($rideprice->price * explode(" ",$fare_distance)[0]);
					        $fare_charge = $this->trip_charge($trip_id,"trip",number_format((float)$fare_price, 2, '.', ''),"Fare Amount","plus");
							if($fare_charge!=true){
								ResponseMessage::error("Trip charges not add");
							}
					        
							$tax_price = 0;
							if($taxes!="notax"){
								foreach ($taxes as $tax) {
									$tax_price = $tax_price + ($fare_price * $tax->percent/100);
									$tax_amount = ($fare_price * $tax->percent/100);
									$fare_tax_charge = $this->trip_charge($trip_id,"taxes",number_format((float)$tax_amount, 2, '.', ''),"TAX ".$tax->tax_name,"plus");
									if($fare_tax_charge!=true){
										ResponseMessage::error("Trip charges not add");
									}
								}
							}

							if($retain=="yes"){
								$retain_charge = (($retain_hours*60) * $rideprice->perminute);
								$fare_price = ($fare_price + $retain_charge);

								$fare_retain_charge = $this->trip_charge($trip_id,"retain",number_format((float)$retain_charge, 2, '.', ''),"Retain charge ","plus");
								if($fare_retain_charge!=true){
									ResponseMessage::error("Trip charges not add");
								}
							}

							$total_fare_price = $fare_price + $tax_price;

							$driver_estimate_arrivel_time_local = TimeHelper::google_plus_time($driver_duration,$current_time,"Y-m-d H:i:s");
							$fare_estimate_end_time_local = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time_local,"g:ia");

		            		$nearDetails = [
		            			"total_fare_price" => number_format((float)$total_fare_price, 2, '.', '') ,
		            			"fare_estimate_end_time" => $fare_estimate_end_time_local,
		            			"fare_estimate_end_time_utc" => $fare_estimate_end_time,
		            			"currency_sign" => $country_setting->currency_sign,
		            			"currency_code" => $country_setting->currency_code,
		            			"category_name" => $category_info->name,
		            			"driver_id" => $nearDriver->driver_id,
		            			"vehicle_type" => $nearDriver->vehicle_type,
		            			"vehicle_category" => $nearDriver->vehicle_category,
		            			"vehicle_person_capacity" => $category_info->min_person_capacity."-".$category_info->max_person_capacity,
		            			"fare_distance" => explode(" ", $fare_distance)[0],
		            			"fare_unit" => explode(" ", $fare_distance)[1],
		            			"fare_duration"=>$fare_duration,
		            			"driver_pickup_time"=>$driver_duration,
		            			"start_address"=>$start_address,
								"end_address"=>$end_address,
		            		];
		            		array_push($temp_cate2,$nearDetails);
		            	}
		            }
	        		$temp_cate = ["id"=>$type->id,"name"=>$type->name,"category"=>$temp_cate2];
					return json_encode(["status"=>1,"data"=>$temp_cate]);
				} else{
					return json_encode(["status"=>0]);
				}	                     							
				return json_encode($temp_cate);
           	} else{
           		return json_encode(["status"=>0]);
           	}
    	} catch (Exception $e) {
    		Exceptions::exception($e);	
    	}
    }

    /**
       * 
       * Payment charges entry
       *
       * @param string $trip_id :- trip id
       * @param string $type :- chares type ('trip', 'taxes', 'waiting', 'other')
       * @param string $amount :- amount to charges
       * @param string $description :- description
       * @param string $action :- action ('plus', 'minus')
       * @return boolean
       *
       * @author     RAVIRAJSINH ZALA <ravirajsinhzala26@gmail.com>
       */

    public function trip_charge($trip_id,$type,$amount,$description,$action)
    {
    	try {
    		if( $trip_id!="" && $type!="" && $amount!="" && $action!="" ){
    			$tripcharge = new Tripcharge;
    			$tripcharge->trip_id = trim($trip_id);
    			$tripcharge->type = trim($type);
				$tripcharge->amount = trim($amount);
				$tripcharge->description = trim($description);
				$tripcharge->action = trim($action);
				if($tripcharge->save()){
    				return true;
				} else{
					return false;
				}
    		} else{
    			return false;
    		}
    	} catch (Exception $e) {
    		
    	}
    }

    public function trip_with_friend(Request $request)
    {
    	try {
    		$rules = [
    			'pickup_latitude' => 'required',
    			'pickup_longitude' => 'required',
    			'drop_latitude' => 'required',
    			'drop_longitude' => 'required',
    			'country_iso' => 'required',
    			'current_time' => '',
    			'friend_latitude' => 'required',
    			'friend_longitude' => 'required',
    			'retain' => '',
    			'retain_hours' => '',
    			'vehicle_type_id' => "required",
                'vehicle_category_id' => "required",
			];
			$customeMessage = [
				'pickup_latitude.required' => 'Please sent pickup latitude.',
				'pickup_longitude.required' => 'Please sent pickup longitude.',
				'drop_latitude.required' => 'Please sent drop longitude.',
				'drop_longitude.required' => 'Please sent drop longitude.',
				'country_iso.required' => 'Please sent country iso code.',
				'friend_latitude.required' => 'Please sent friend latitude.',
				'friend_longitude.required' => 'Please sent friend longitude.',
				'vehicle_type_id.required' => 'Please sent vehicle type.',
				'vehicle_category_id.required' => 'Please sent vehicle category.',
			];
			$validator = Validator::make($request->all(),$rules,$customeMessage);
	        if($validator->fails()) {
	            $errors = $validator->errors();
				ResponseMessage::error($errors->first());
	        } else{
	        	$pickup_latitude = trim(strip_tags($request->pickup_latitude));
	        	$pickup_longitude = trim(strip_tags($request->pickup_longitude));
	        	$drop_latitude = trim(strip_tags($request->drop_latitude));
	        	$drop_longitude = trim(strip_tags($request->drop_longitude));
	        	$friend_latitude = trim(strip_tags($request->friend_latitude));
	        	$friend_longitude = trim(strip_tags($request->friend_longitude));
	        	$country_iso = trim(strip_tags($request->country_iso));
	        	$current_time = trim(strip_tags($request->current_time));
	        	$vehicle_type_id = trim(strip_tags($request->vehicle_type_id));
	        	$vehicle_category_id = trim(strip_tags($request->vehicle_category_id));
	        	$retain = trim(strip_tags($request->retain));
	        	$with_friend = "yes";

	        	if(Country::where('code_2',$country_iso)->exists()){
		        	$country_setting = Country::where('code_2',$country_iso)->get()->first();
		        	$taxes = Taxe::getTaxes($country_setting->id);
		        	$radius = config('constants.near.radius');

		      //   	$userNearestList = Availabledriver::select("vehicle_type","vehicle_category")
	       //  					->where('status','on')
	       //  					->where('vehicle_type',$vehicle_type_id)
	       //  					->where('vehicle_category',$vehicle_category_id)
								// ->whereRaw( DB::raw( "(6371 * acos( cos( radians($pickup_latitude) ) * cos( radians( latitude ) )  * 
			     //                      cos( radians( longitude ) - radians($pickup_longitude) ) + sin( radians($pickup_latitude) ) * sin( 
			     //                      radians( latitude ) ) ) ) < $radius ")
			     //                 )->groupby('vehicle_type','vehicle_category')->orderby('vehicle_type')->get()->first();

						$nearDriver = DB::table('available_drivers')
										->where('status','on')
										->where('vehicle_type',$vehicle_type_id)
	        							->where('vehicle_category',$vehicle_category_id)
										->select("driver_id","vehicle_type","vehicle_category","latitude","longitude",DB::raw( "(6371 * acos( cos( radians($pickup_latitude) ) * cos( radians( latitude ) )  *  cos( radians( longitude ) - radians($pickup_longitude) ) + sin( radians($pickup_latitude) ) * sin(  radians( latitude ) ) ) ) as distance") )
			                     		->orderby('vehicle_type','ASC')
			                     		->orderby('vehicle_category','ASC')
			                     		->orderby('distance','ASC')
			                     		->get()->first();
			            
			            $temp_cate2 = [];
			            $vehicle_category_id = "";
			            if($nearDriver) {
			            	if($vehicle_category_id=="" || $vehicle_category_id!=$nearDriver->vehicle_category){
			            		$vehicle_category_id=$nearDriver->vehicle_category;

			            		$rideprice = Rideprice::where('vehicle_type_id',$nearDriver->vehicle_type)->where('vehicle_catehory_id',$nearDriver->vehicle_category)->where('unit',$country_setting->unit)->get()->first();
			            		$category_info = Vehiclecategory::where("vehicle_type_id",$nearDriver->vehicle_type)->where("id",$nearDriver->vehicle_category)->get()->first();

			            		$driver_current_latitude = $nearDriver->latitude;
			            		$driver_current_longitude = $nearDriver->longitude;
			            		$driver_distance = 0;
								$driver_duration = 0;
								$fare_distance = 0;
								$fare_duration = 0;
								$fare_price = 0;

			            		$driver_info = DistanceHelper::drivingDistance($driver_current_latitude,$driver_current_longitude,$pickup_latitude,$pickup_longitude,$country_setting->unit);

			            		if(isset($driver_info["routes"])){
						        	foreach ($driver_info["routes"] as $key => $value) {
						        		foreach ($value["legs"] as $lkey => $lvalue) {
						        			$driver_distance = $lvalue["distance"]["text"];
						        			$driver_duration = $lvalue["duration"]["text"];			        			
						        		}
						        	}
					        		$driver_estimate_arrivel_time = TimeHelper::google_plus_time($driver_duration,"","Y-m-d H:i:s");
						        } else{
									ResponseMessage::error("Route not found");
						        }

						        $customer_info = ""; 
						        if($with_friend=="no"){
						        	$customer_info = DistanceHelper::drivingDistance($pickup_latitude,$pickup_longitude,$drop_latitude,$drop_longitude,$country_setting->unit);
						        } else{
						        	$customer_info = DistanceHelper::drivingDistance($pickup_latitude,$pickup_longitude,$drop_latitude,$drop_longitude,$country_setting->unit,$friend_latitude,$friend_longitude);
						        }
						        if(isset($customer_info["routes"])){
						        	foreach ($customer_info["routes"] as $key => $value) {
						        		foreach ($value["legs"] as $lkey => $lvalue) {
						        			$fare_distance = $lvalue["distance"]["text"];
						        			$fare_duration = $lvalue["duration"]["text"];			        			
						        		}
						        	}
					        		$fare_estimate_end_time = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time,"Y-m-d H:i:s");
						        } else{
									ResponseMessage::error("Route not found");						        	
						        }

						        $fare_price = ($rideprice->price * explode(" ",$fare_distance)[0]);

								$tax_price = 0;
								if($taxes!="notax"){
									foreach ($taxes as $tax) {
										$tax_price = $tax_price + ($fare_price * $tax->percent/100);
									}
								}
								if($retain=="yes"){
									$retain_charge = (($retain_hours*60) * $rideprice->perminute);
									$fare_price = ($fare_price + $retain_charge);
								}
								$total_fare_price = $fare_price + $tax_price;

								$driver_estimate_arrivel_time_local = TimeHelper::google_plus_time($driver_duration,$current_time,"Y-m-d H:i:s");
								$fare_estimate_end_time_local = TimeHelper::google_plus_time($fare_duration,$driver_estimate_arrivel_time_local,"g:ia");

			            		$nearDetails = [
			            			"total_fare_price" => number_format((float)$total_fare_price, 2, '.', '') ,
			            			// "fare_estimate_end_time" => $fare_estimate_end_time_local,
			            			// "fare_estimate_end_time_utc" => $fare_estimate_end_time,
			            			"currency_sign" => $country_setting->currency_sign,
			            			// "category_name" => $category_info->name,
			            			// "driver_id" => $nearDriver->driver_id,
			            			// "vehicle_type" => $nearDriver->vehicle_type,
			            			// "vehicle_category" => $nearDriver->vehicle_category,
			            			// "vehicle_person_capacity" => $category_info->min_person_capacity."-".$category_info->max_person_capacity,
			            			"fare_distance" => explode(" ", $fare_distance)[0],
			            			"fare_unit" => explode(" ", $fare_distance)[1],
			            			"fare_duration"=>$fare_duration,
			            			// "driver_pickup_time"=>$driver_duration,
			            		];
			            		ResponseMessage::success("success",$nearDetails);
			            	}
			            }
	           	} else{
	           		ResponseMessage::error("Country not found");
	           	}

	        }
    	} catch (Exception $e) {
    		Exceptions::exception($e);	
    	}
    }
}