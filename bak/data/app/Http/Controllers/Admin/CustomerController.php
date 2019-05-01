<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\Exceptions;

class CustomerController extends Controller
{
    public function index()
    {
    	try {
    		$data['customer'] = User::orderby('id','DESC')->get();
            $data['data']="";
    		return view('admin.customer.customer')->with($data);
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }

    public function deleteindex()
    {
        try{
            $data['customer'] = array();
            // $data['customer']=User::onlyTrashed()->get();
            return view('admin.customer.customer')->with($data);
        }catch(\Exception $e){
            $Exception=New Exceptions;
            $Exceptions->sendException($e);
        }
    }

    public function getstatus($status,$id)
    {
        try{
                if($status=='in-active'){
                    $status='active';
                }else{
                    $status='in-active';
                }
                $user=User::find($id);
                $user->status=$status;
                $user->save();
           return back();
           }catch(\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }
    }
    public function delete(Request $request)
    {
        if($request->id!=null)
        {
            $id=$request->id;
            $user=User::where('id',$id)->delete();
        }
        return back();
    }
}
