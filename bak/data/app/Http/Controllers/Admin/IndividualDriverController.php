<?php

namespace App\Http\Controllers\Admin;           

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\RandomHelper;
use App\Model\Exceptions;
use App\Model\Driver\Driver;
use App\Model\Vehicletype;
use App\Model\Country;
use App\Model\Vehiclecategory;
use Validator;
use Auth;

class IndividualDriverController extends Controller
{
   public $rules=[
    'vehicle_type' => 'required',
    'vehicle_category' => 'required', 
    'name'    => 'required',
    'email'   =>'required|email',
    'country_code' => 'required',
    'contact_no' => 'required|numeric|digits:10',
    'profile' =>'image'];

    public $customMessage=[
    'vehicle_type.required' => 'Please Enter a Vehicle Type.',
    'vehicle_category.required' => 'Please Enter a Vehicle Category.',
    'name.required' => 'Please Enter a Name.',
    'email.required' => 'Please Enter a Email.',
    'country_code.required' => 'Please Enter a Country Code.',
    'contact_no.required' => 'Please Enter a Contact Number.',
    'contact_no.numeric' => 'Please Enter a Numeric Value.',
    'profile.image' => 'Please Select Only Image.'];

    public function index()
    {
        try{
          $data['drivers']=Driver::leftjoin('vehicle_types','drivers.vehicle_type','=','vehicle_types.id')->leftjoin('vehicle_categorys','drivers.vehicle_category','=','vehicle_categorys.id')->select('drivers.*','vehicle_types.name as vehicle_types_name','vehicle_categorys.name as vehicle_categorys_name')->paginate(10);
          return view('admin.individual_driver.individual_driver')->with($data);
        }catch(\Exception $e){
           $Exceptions=New Exceptions;
           $Exceptions->sendException($e); 
        }
    }
     
  public function create(Request $request)
  { 
        try{
           $data['drivers']="";
           $data['vehicle_type']=Vehicletype::pluck('name','id');
           $data['vehicle_category']=Vehiclecategory::get();
           $data['country']=Country::get();
           return view('admin.individual_driver.individual_driver_add')->with($data);
        }catch(\Exception $e){
            $Exceptions=New Exceptions;
            $Exceptions->sendException($e);
        }
  }

    public function edit($id)
    {
       try{
         if(Driver::where('id',$id)->exists()){
            $data['drivers']=Driver::where('id',$id)->get()->first();
            $data['vehicle_type']=Vehicletype::pluck('name','id');
            $data['vehicle_category']=Vehiclecategory::get();
            $data['country']=Country::get();
            return view('admin.individual_driver.individual_driver_add')->with($data);
         }

       }catch(\Exception $e){
        $Exceptions=New Exceptions;
        $Exceptions->sendException($e);
       }
  } 

  public function get_vehicle($id)
  {
     $get_vehicle=Vehiclecategory::where('vehicle_type_id',$id)->pluck("name","id");
     return json_encode($get_vehicle);
  }

 public function store(Request $request)
   {
      try{
            $validator=Validator::make($request->all(),$this->rules,$this->customMessage); 
            if($validator->fails()){
                return back()->withInput()->withErrors($validator->errors()); 
                } else{
                    $invite_code = RandomHelper::inviteCode();
                $id=$request->id;
                $vehicle_type=trim($request->vehicle_type);
                $vehicle_category=trim($request->vehicle_category);
                $name=trim($request->name);
                $email=trim($request->email);
                $country_code=trim($request->country_code);
                $contact_no=trim($request->contact_no); 
                $status=$request->status;
                if($id!=""){
                    $driver=Driver::find($id);
                } else{
                    $driver=New Driver();
                }
                $driver->vehicle_type=$vehicle_type;
                $driver->vehicle_category=$vehicle_category;
                $driver->name=$name;
                $driver->email=$email;
                $driver->country_code=$country_code;
                $driver->contact_no=$contact_no;
                $driver->status=$status;
                $driver->password="";

                if($driver->save()){
                if($request->has('profile')){
                    $time=md5(time());
                    $file=$request->profile;
                    $extension = $file->getClientOriginalExtension();
                    $profile=$time.'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('driver/'.$invite_code.'/profile/'),$profile);
                    $profile = 'driver/'.$invite_code.'/profile/'.$profile;
                    $profile_update=Driver::find($driver->id);
                    $profile_update->profile=$profile;
                    $profile_update->save();
                }
               if($id!=""){
                    return redirect()->route('admin.individual_driver')->with('success','individual Driver Succesfully updated');
                }else{
                    return redirect()->route('admin.individual_driver')->with('success','Individual driver Succesfully Added');
                } 
             }
                    return back()->with('error','Something wrong please try again latter');
            } 
         
        }catch(\Exception $e){
         $Exceptions =New Exceptions;
         $Exceptions->sendException($e); 
        }
   }

     public function delete(Request $request)
    {
    	
    	    if($request->id!=null)
    	  {	
    	       $id=$request->id;
    	       $driver=Driver::where('id',$id)->delete();
           }
    	     return back();
    }
}
