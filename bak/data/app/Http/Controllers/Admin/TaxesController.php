<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Taxe;
use App\Model\Exceptions;
use App\Model\Country;
use Validator;
use Auth;

class TaxesController extends Controller
{

	public $rules=[
	'country_id' => 'required',
	'tax_name'	 => 'required',
	'percent'    => 'required|numeric',
	];

	public $customeMessage=[
    'country_id.required' => 'Please Select a Country',
    'tax_name.required'   => 'Please Enter Tax Name',
    'percent.required'    => 'Please Enter a Percentage',
    'percent.numeric'     => 'Please Enter a number value'
	];
      
  public function index()
   {
   	try{
   	 $data['taxes']=Taxe::join('countrys','taxes.country_id','=','countrys.id')->select('taxes.*','countrys.name as countrys_name')->get();	

     return view('admin.Taxes.Taxes')->with($data);

   	 }catch(\Exception $e){
   	 	$Exceptions=New Exceptions;
   	 	$Exceptions->sendException($e);
   	 }
   }

   public function create()
   {
       try{
      $data['taxes']='';
      $data['countrys']=Country::get();
      return view('admin.Taxes.Taxes_add')->with($data); 	
      }catch(\Exception $e){
       	$Exceptions=New Exceptions;
       	$Exceptions->sendException($e);
       }
   }
   
   public function edit($id)
   {
   	   try{
   	   	if(Taxe::where('id',$id)->exists()){
           $data['taxes']=Taxe::where('id',$id)->get()->first();
           $data['countrys']=Country::get();
           return view('admin.Taxes.Taxes_add')->with($data);
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
           $id=$request->id;
           $country_id=$request->country_id;
           $tax_name=$request->tax_name;
           $percent=$request->percent;
           if($id!=""){
           	$tax=Taxe::find($id);
           }else{
           	$tax=New Taxe();
           }
           $tax->country_id=$country_id;
           $tax->tax_name=$tax_name;
           $tax->percent=$percent;
           $tax->created_by=Auth::guard('admin')->user()->id;
           $tax->save();
           if($id!=""){
           	return redirect()->route('admin.taxes')->with('success','Tax succesfully updated');
           }else{
           	return redirect()->route('admin.taxes')->with('success','Tax succesfully Inserted');
           }
           return back()->with('error','something wrong please try again latter');
          }

       }catch(\Exception $e){
       	$Exceptions=New Exceptions;
       	$Exceptions->sendException($e);
       }

   }

  public function delete($id)
   {
   	 try{
   	  if($id!=""){
   	  	$taxes=Taxe::where('id',$id)->delete();
   	  }
   	  return back();
   	 }catch(\Exception $e){
   		$Exceptions=New Exceptions;
   		$Exceptions->sendException($e);
   	  }	 
   }

}