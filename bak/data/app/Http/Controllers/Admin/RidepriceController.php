<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Rideprice;
use App\Model\Vehicletype;
use App\Model\VehicleCategory;
use App\Model\Exceptions;
use Validator;
use Auth;

class RidepriceController extends Controller
{
    public $rules = [
        'vehicle_type_id' => 'required',
        'vehicle_category_id' => 'required',
        'unit' => 'required',
        'price' => 'required|numeric',
        'perminute' => 'required|numeric'
    ];

    public $customeMessage = [
        'vehicle_type_id.required' => 'Please enter vehicleType.',
        'vehicle_category_id.required' => 'Please enter VehicleCategory.',
        'unit.required' => 'Please enter a unit.',
        'price.required' =>'Please enter a Price.',
        'price.numeric' =>'Please enter a Numeric Value',
        'perminute.required' =>'Please enter a perminute.',
        'perminute.numeric' =>'Please enter a Numeric value'
    ];

    public function index()
    {
    	try{
           $data['ride_price']=Rideprice::leftjoin('vehicle_types','ride_price.vehicle_type_id','=','vehicle_types.id')->leftjoin('vehicle_categorys','ride_price.vehicle_category_id','=','vehicle_categorys.id')->select('ride_price.*','vehicle_types.name as vehicle_types_name','vehicle_categorys.name as vehicle_categorys_name')->paginate(10);
           return view('admin.ride_price.ride_price')->with($data);
    	   }catch(\Exception $e){
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }
     
     public function create()
    {

       try{
          $data['ride_price']="";
          $data['vehicle_type']=Vehicletype::get();
          $data['vehicle_category']=VehicleCategory::get();
          return view('admin.ride_price.ride_price_add')->with($data);

        }catch(\Exception $e){
          $Exceptions = New Exceptions;
          $Exceptions->sendException($e);
        }
    }

    public function edit($id)
    {
    	try{
          if(Rideprice::where('id',$id)->exists()){
          	$data['ride_price']=Rideprice::where('id',$id)->get()->first();
            $data['vehicle_type']=Vehicletype::get();
            $data['vehicle_category']=VehicleCategory::get(); 
            return view('admin.ride_price.ride_price_add')->with($data);
          }
   
    	   }
    	catch(\Exception $e){
    	 $Exceptions = New Exceptions;
    	 $Exceptions->sendException($e);	
    	}
    }

    public function store(Request $request)
    {
         try{
              $Validator=Validator::make($request->all(),$this->rules,$this->customeMessage);
              if($Validator->fails()){
              return back()->withInput()->withErrors($Validator->errors());
           }else{
           $id=$request->id;
           $vehicle_type_id=$request->vehicle_type_id;
           $vehicle_category_id=$request->vehicle_category_id;
           $unit=$request->unit;
           $price=$request->price;
           $perminute=$request->perminute;
           if($id!=""){
            $rideprice=Rideprice::find($id);
           }else{
            $rideprice=New Rideprice();
           }
           $rideprice->vehicle_type_id=$vehicle_type_id;
           $rideprice->vehicle_category_id=$vehicle_category_id;
           $rideprice->unit=$unit;
           $rideprice->price=$price;
           $rideprice->perminute=$perminute;
           $rideprice->created_by=Auth::guard('admin')->user()->id;

           $rideprice->save();
           if($id!=""){
            return redirect()->route('admin.ride_price')->with('success','RidePrice Succesfully updated');
            }else{
              return redirect()->route('admin.ride_price')->with('success','RidePrice Succesfully Added');
            }
            return back()->with('error','Something wrong please try again latter');
           }
         
        }catch(\Exception $e){
         $Exceptions =New Exceptions;
         $Exceptions->sendException($e); 
    	}
    }

   

    public function delete($id)
    { 
    	try{
    		if($id!=""){
          $ride_price=Rideprice::where('id',$id)->delete();		
          }
          return back();
    	}catch(\Exception $e){
    	 $Exceptions = New Exceptions;
    	 $Exceptions->sendException($e);	
    	}
    }
}