<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Vehiclecategory;
use App\Model\Vehicletype;
use App\Model\Exceptions;
use Validator;
use Auth;
use Hash;


class VehicleCategoryController extends Controller
{
	public $rules=[
       'vehicle_type_id' => 'required',
        'name' => 'required|max:30',
        'min_person_capacity' => 'numeric|max:100|lt:max_person_capacity',
        'max_person_capacity' => 'numeric|max:100',
        'status' => 'required',
 ];
     public	$customeMessage=[
       'vehicle_type_id.required'=> 'Please Choose a Type', 
       'name.required' => 'please Enter a Categoey Name',
       'name.max' => 'Please Enter a Maximum 30 Character',
       'min_person_capacity.numeric' =>'Please Enter a Numeric Value',
        'min_person_capacity.lt' =>'Please Enter Less Than Maximum Person Capacity',
       'max_person_capacity.numeric' => 'Please Enter a  Numeric Value',
       'status.required' => 'Please select Status'
 ];

	 public function index()
	 {
      try{
      	 $data['vehicle_categorys'] = Vehiclecategory::join('vehicle_types','vehicle_categorys.vehicle_type_id','=','vehicle_types.id')->select('vehicle_categorys.*','vehicle_types.name as vehicle_types_name')->orderby('name','ASC')->paginate(10);
      	   return view('admin.vehicle_category.vehicle_category')->with($data);
         }catch(\Exception $e){ 
            $Exceptions=New Exceptions;
            $Exceptions->sendException($e);
      }
   }

    public function create()
    {
       try{
          $data[]="";
          $data['vehicle_type']=vehicletype::orderby('name','ASC')->get();
          return view('admin.vehicle_category.vehicle_category_add')->with($data);
        }catch(\Exception $e){
          $Exceptions=New Exceptions;
          $Exceptions->sendException($e);
        }
    }

    public function edit($id)
    {
      try{
              if(Vehiclecategory::where('id',$id)->exists()){
              $data['vehicle_categorys']=Vehiclecategory::where('id',$id)->get()->first();
           	  $data['vehicle_type']=Vehicletype::orderby('name','ASC')->get();
              return view('admin.vehicle_category.vehicle_category_add')->with($data);
              }else{
                return back()->with('error','Vehiclecategory not exists please try again..');
              }
          }catch(\Exception $e){
            $Exceptions=New Exceptions;
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
       	    	$vehicle_type_id=$request->vehicle_type_id;
       	    	$name=trim($request->name);
       	    	$min_person_capacity=$request->min_person_capacity;
       	    	$max_person_capacity=$request->max_person_capacity;
       	    	$status=$request->status;
       	    	if($request->id!=""){
       	    		$vehiclecategory=Vehiclecategory::find($request->id);
       	    	}else{
       	    		$vehiclecategory=New Vehiclecategory();
       	    	}	
       	    	$vehiclecategory->vehicle_type_id = $vehicle_type_id;
       	    	$vehiclecategory->name=$name;
       	    	$vehiclecategory->min_person_capacity=$min_person_capacity;
       	    	$vehiclecategory->max_person_capacity=$max_person_capacity;
              $vehiclecategory->status=$status;
              $vehiclecategory->created_by=Auth::guard('admin')->user()->id;
              $vehiclecategory->save();
       	      if($request->id!=""){
       	       return redirect()->route('admin.vehicle_category')->with('success','Vechile Category succesfully updated');
       	      }else{
       	    	return redirect()->route('admin.vehicle_category')->with('success','Vechile Category succesfully Added');
       	      }
       	      return back()->with('error','Something wrong please try agian latter');
              }

            }catch(\Exception $e){
       	       $Exceptions=New Exceptions;
       	       $Exceptions->sendException($e);
          }   
    }


  public function delete(Request $request)
  {
     	$id=$request->did;
     	if($id!=""){
         $vehicle_categorys=vehiclecategory::where('id',$id)->delete();
      }
     	return back();
  }

}
