<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Model\Country;
use App\Model\Exceptions;
use Validator;
use Auth;
use hash;

class ProfileController extends Controller
{
      public $rules=[
          'firstname'=>'required',
          'lastname'=>'required',
          'country_code'=>'required',
          'mobile_no'=>'required|digits:10',
          'email' => 'required|email',
          'profile' =>'image'];
       public $CustomeMessage=[
           'firstname.required'=>"Please Enter a Firstname",
           'lastname.required' =>"Please Enter a Lastname",
           'country_code.required' =>"Please Select a CountryCode",
           'mobile_no.required' =>"Please Enter a Mobile Number",
           'email.required' =>"Please Enter a Email Address",
           'profile.image' => 'Please Select Only Image.'];
   
  public function index()
  {
    	try {
    		$data['profile']=Auth::guard('admin')->user();
        $data['country']=Country::orderby('phone_code','ASC')->get();
    		return view('admin.profile.profile')->with($data);
       	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
        $Exceptions->sendException($e);
    	 }
  }

  public function store(Request $request)
   { 
   	    try{     
                  $validator=validator::make($request->all(),$this->rules,$this->CustomeMessage);
            	   if($validator->fails() ){
                  return back()->withInput()->withErrors($validator->errors());
            	   }else{
              	   $firstname=trim($request->firstname);
              	   $lastname=trim($request->lastname);
              	   $country_code=trim($request->country_code);
                	 $contact_no=trim($request->mobile_no);	
              	   $email=trim($request->email);
              	   $profile=""; 

          	     	if($request->id!=""){
            	    $admin=Admin::find($request->id);	
           		   }else{
            		  $admin=new Admin;
            	   } 

	                 $admin->firstname=$firstname;
             	     $admin->lastname=$lastname;
             	     $admin->country_code=$country_code;
             	     $admin->mobile_no=$contact_no;
             	     $admin->email=$email;
                   if($admin->save()){
             	      if($request->has('profile')){
             	      $file=$request->profile;
             	      $time=md5(time());
             	      $profile=$time.'.'.$file->getClientOriginalExtension();
             	      $path="images/profile/".$admin->id."/file";
             	      $file->move(public_path($path),$profile);
             	      $profile_update=Admin::find($admin->id);
             	      $profile_update->profile=$path."/".$profile;
                    $profile_update->save();
             	 }
             	        if($request->id!=""){
             		     return redirect()->route('admin.dashboard')->with('success','Admin profile succesfully updated');
             	        }else{
             		       return redirect()->route('admin.dashboard')->with('success','Admin profile succesfully Register');
                     }
                   }
                   else{
                        return back()->with("Something was wrong Please Try Again");
             	        }
                    }
         } catch(\Exceptions $e){
		    $Exceptions=new Exceptions;
		    $Exceptions->sendException($e);
     }
  }
}
   
