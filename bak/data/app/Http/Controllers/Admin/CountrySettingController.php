<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Country;
use App\Model\Exceptions;

class CountrySettingController extends Controller
{
    public function index()
    {
    	try {
    		$data['countries'] = Country::orderby('id','ASC')->paginate(10);
            return view('admin.country_setting.country_setting')->with($data);
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }

    public function edit($id)
    {
        try {
        	if(Country::where('id',$id)->exists()){
                $data['country'] = Country::where('id',$id)->get()->first();
                return view('admin.country_setting.edit_country')->with($data);
            } else{
                return back()->with('error','Country not exists please try again.');   
            }
        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }   
    }

     public function update(Request $request)
    {
        try {
            $status=$request->status;
            $id=$request->id;
            $unit = $request->country_unit_id;
            if(isset($id)&&$id!=""){
               $country = Country::find($id);
               $country->status=$status;
               $country->unit=$unit;
               $country->save();
               return redirect()->route('admin.country_setting');
            }else{
                return back()->with('error','Country not exists please try again.');   
            }
        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }
    }

}
