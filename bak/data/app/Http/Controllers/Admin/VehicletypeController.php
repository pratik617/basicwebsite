<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Vehicletype;
use App\Model\Exceptions;
use Validator;
use Auth;
use Hash;

class VehicletypeController extends Controller
{
    public $rules=[
     'name' => 'required|max:20',
     'status' => 'required'
    ];

   public $customeMessage=[
    'name.required' => 'Please Enter a Vehicle Name',
    'name.max' => 'Please Enter a Maximum 20 Character',
    'status.required' =>'Please Enter a Vehicle Status',
   ];



   public function index()
   {
        try{
          $data['vehicletype'] =vehicletype::select('id','name','status')->orderby('name','ASC')->
          paginate(10);
          return view('admin.vehicle_type.vehicle_type')->with($data);
        }catch(\Exception $e){
        	$Exceptions=New Exceptions;
            $Exceptions->sendException($e);
        }
   }

   public function create()
   {
      try{
          $data[]="";
          $data['vehicle_type']=vehicletype::get();
          return view('admin.vehicle_type.vehicle_type_add')->with($data);
        }catch(\Exception $e){
          $Exceptions=New Exceptions;
          $Exceptions->sendException($e);
      }
   }

  public function edit($id)
  {
 	    try{
          if(Vehicletype::where('id',$id)->exists()){
              $data['vehicletype'] = vehicletype::where('id',$id)->get()->first();
              return view('admin.vehicle_type.vehicle_type_add')->with($data);
           } else{
              return back()->with('error','Company not exists please try again.');   
           }

        }catch (\Exception $e) {
            $Exceptions = New Exceptions;
           $Exceptions->sendException($e);
       }
  } 

  public function store(Request $request)
  {
    try{ 
          $validator = Validator::make($request->all(),$this->rules,$this->customeMessage);
          if( $validator->fails() ){
          return back()->withInput()->withErrors($validator->errors());
          } else{
          $id=$request->id;
          $name=trim($request->name);
            $status=trim($request->status);
          }
          if($request->id!=""){
            $vehicletype=Vehicletype::find($request->id);
          }else{
            $vehicletype=New Vehicletype;
          }
          $vehicletype->name=$name;
            $vehicletype->status=$status;
          $vehicletype->created_by=Auth::guard('admin')->user()->id;
            $vehicletype->save();
          if($request->id!=""){
            return redirect()->route('admin.vehicle_type')->with('success','Vechiletype updated succesfully');
          }else{
            return redirect()->route('admin.vehicle_type')->with('success','Vechiletype insert succesfully');
          }  
          return redirect()->route('admin.vehicle_type');    
    }catch (\Exception $e){
       $Exceptions =New Exceptions;
       $Exceptions->sendException($e);
    }
  }

  public function delete(Request $request)
  {
  	$id=$request->did;
  	if($id!=""){
  		$VehicleTypeController=Vehicletype::where('id',$id)->delete();
  	}
  	return back();
  }
}
