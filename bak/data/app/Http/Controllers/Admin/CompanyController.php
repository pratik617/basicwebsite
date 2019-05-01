<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Exceptions;
use Mail;
use App\Model\Company\Company;
use App\CompanyAdmin;
use App\Model\Country;
use Validator;
use Auth;
use Hash;

class CompanyController extends Controller
{
    public $rules = [
        'company_name' => 'required',
        'company_contact_code' => 'required',
        'company_city' => 'required',
        'company_address' => 'required',
        'company_logo' => 'image'
    ];
    public $customeMessage = [
        'company_name.required' => 'Please enter company name.',
        'company_email.required' => 'Please enter company email address.',
        'company_email.email' => 'Please enter valid email address.',
        'company_email.unique' => 'Email already exitst.',
        'company_contact_code.required' => 'Please enter country code.',
        'company_contact_number.required' => 'Please enter contact number.',
        'company_contact_number.digits_between' => 'Please enter valid contact number.',
        'company_contact_number.unique' => 'Contact number already exitst.',
        'company_city.required' => 'Please enter city.',
        'company_address.required' => 'Please enter company address.',
        'company_logo.image' => 'Company logo must be image format.'
    ];

    public $admin_rules = [
        'company_id' => 'required',
        'firstname' => 'required',
        'lastname' => 'required',
        'country_code' => 'required',
        'password' => 'required',
        'status' => 'required',
        'profile' => 'image'
    ];
    public $admin_custome_message = [
        'company_id.required' => 'Please select company.',
        'firstname.required' => 'Please enter firstname.',
        'lastname.required' => 'Please enter lastname.',
        'country_code.required' => 'Please enter country code.',
        'contact_number.required' => 'Please enter contact no.',
        'contact_number.unique' => 'Contact no already exists.',
        'email.required' => 'Please enter email address.',
        'email.unique' => 'Email address already exists.',
        'password.required' => 'Please enter password.',
        'status.required' => 'Please select status.',
        'profile.image' => 'Please select only image.',
    ];

    public function index()
    {
    	try {
    		$data['companys'] = Company::select('id','name','city','logo')->orderby('id','DESC')->paginate(10);
            $data['country']=Country::get();
    		return view('admin.company.company')->with($data);
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }

    public function create()
    {
    	try {
    		$data[] = "";
            $data['country']=Country::get();
    		return view('admin.company.add')->with($data);
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }

    public function edit($id)
    {
        try {
            if(Company::where('id',$id)->exists()){
                $data['company'] = Company::where('id',$id)->get()->first();
                $data['country']=Country::get();
                return view('admin.company.add')->with($data);
            } else{
                return back()->with('error','Company not exists please try again.');   
            }

        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }
    }

    public function store(Request $request)
    {
    	try {
            if($request->id!=""){
                $old_data = Company::where('id',$request->id)->get()->first();
                $old_email = $old_data->email;
                $old_contact_no = $old_data->contact_no;
                if( $old_email != $request->company_email ){
                    $this->rules['company_email'] = 'required|email|unique:companys,email';
                } 
                if( $old_contact_no != $request->company_contact_number ){
                    $this->rules['company_contact_number'] = 'numeric|digits_between:6,15|unique:companys,contact_no';    
                }
            } else{
                $this->rules['company_email'] = 'required|email|unique:companys,email';
                $this->rules['company_contact_number'] = 'numeric|digits_between:6,15|unique:companys,contact_no';
            }
            $validator = Validator::make($request->all(),$this->rules,$this->customeMessage);
            if( $validator->fails() ){
                return back()->withInput()->withErrors($validator->errors());
            } else{
                $company_name = trim($request->company_name);
                $company_email = trim($request->company_email);
                $company_contact_code = trim($request->company_contact_code);
                $company_contact_number = trim($request->company_contact_number);
                $company_city = trim($request->company_city);
                $company_address = trim($request->company_address);
                $company_logo = "";
                
                if($request->id!=""){
                    $company = Company::find($request->id);
                } else{
                    $company = New Company;
                }

                $company->name = $company_name;
                $company->email = $company_email;
                $company->country_code = $company_contact_code;
                $company->contact_no = $company_contact_number;
                $company->city = $company_city;
                $company->address = $company_address;
                $company->created_by = Auth::guard('admin')->user()->id;
                if($company->save()){
                    if($request->has('company_logo')){
                        $file = $request->company_logo;
                        $time = md5(time());
                        $company_logo = $time.'.'.$file->getClientOriginalExtension();
                        $path = "company/".$company->id."/logo";
                        $file->move(public_path($path),$company_logo);
                        $company_update = Company::find($company->id);
                        $company_update->logo = $path."/".$company_logo;
                        $company_update->save();
                    }
                    if($request->id!=""){
                        return redirect()->route('admin.companies')->with('success','Company successfully update.');
                    } else{
                        return redirect()->route('admin.companies')->with('success','Company successfully register.');
                    }
                } else{
                    return back()->with('error','Something was wrong please try again.');      
                }
            }
    	} catch (\Exception $e) {
    		$Exceptions = New Exceptions;
            $Exceptions->sendException($e);
    	}
    }

    public function delete($id)
    {
        try {
            if(Company::where('id',$id)->exists()){
                
            } else{
                return back()->with('error','Company not exists please try again.');   
            }
        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }
    }

    public function company_admin()
    {
        try {
            $data['company_admin'] = CompanyAdmin::select('company_admins.*','companys.name as company_name')
                                ->leftjoin('companys','companys.id','=','company_admins.company_id')
                                ->orderby('id','DESC')
                                ->paginate(10);
              $data['country']=Country::get();                  
            return view('admin.company.company_admin')->with($data);
        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }   
    }

    public function create_company_admin()
    {
        try {
            $data['company'] = Company::orderby('name','ASC')->get();
            $data['country']=Country::get();
            return view('admin.company.add_admin')->with($data);
        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }   
    }

    public function store_company_admin(Request $request)
    {
        try {
            if($request->id!=""){
                $old_data = CompanyAdmin::where('id',$request->id)->get()->first();
                $old_email = $old_data->email;
                $old_contact_no = $old_data->mobile_no;
                $old_password = $old_data->password;
                if( $old_email != $request->email ){
                    $this->admin_rules['email'] = 'required|email|unique:company_admins,email';    
                }
                if( $old_contact_no != $request->contact_number ){
                    $this->admin_rules['contact_number'] = 'numeric|digits_between:6,15|unique:company_admins,mobile_no';
                }
                if( Hash::check($request->password,$old_password) ){
                } else{
                    $this->admin_rules['password'] = 'required';    
                }
            } else{
                $this->admin_rules['contact_number'] = 'required|numeric|digits_between:6,15|unique:company_admins,mobile_no';
                $this->admin_rules['email'] = 'required|email|unique:company_admins,email';
                $this->admin_rules['password'] = 'required';
            }
            $validator = Validator::make($request->all(),$this->admin_rules,$this->admin_custome_message);
            if( $validator->fails() ){
                return back()->withInput()->withErrors($validator->errors());
            } else{ 
                $company_id = trim($request->company_id);
                $firstname = trim($request->firstname);
                $lastname = trim($request->lastname);
                $country_code = trim($request->country_code);
                $mobile_no = trim($request->contact_number);
                $email = trim($request->email);
                $password = trim($request->password);
                $status = trim($request->status);
                if($request->id!=""){
                    $company_admin = CompanyAdmin::find($request->id);
                    if($request->password == "******"){
                    } else{
                        $company_admin->password = Hash::make($password);
                    }
                } else{
                    $company_admin = New CompanyAdmin;
                    $company_admin->password = Hash::make($password);
                }

                $company_admin->company_id = $company_id;
                $company_admin->firstname = $firstname;
                $company_admin->lastname = $lastname;
                $company_admin->country_code = $country_code;
                $company_admin->mobile_no = $mobile_no;
                $company_admin->email = $email;
                $company_admin->status = $status;
                $company_admin->created_by = Auth::guard('admin')->user()->id;

                if($company_admin->save()){
                    if($request->has('profile')){
                        $file = $request->profile;
                        $time = md5(time());
                        $profile = $time.'.'.$file->getClientOriginalExtension();
                        $path = "company/".$company_id."/admin/profile";
                        $file->move(public_path($path),$profile);
                        $company_update = CompanyAdmin::find($company_admin->id);
                        $company_update->profile = $path."/".$profile;
                        $company_update->save();
                    }
                    if($request->id!=""){
                        return redirect()->route('admin.company.admin')->with('success','Company admin successfully update.');
                    } else{
                        return redirect()->route('admin.company.admin')->with('success','Company admin successfully register.');
                    }
                } else{
                    return back()->with('error','Something was wrong please try again.');
                }
            }
        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }
    }

    public function edit_company_admin($id)
    {
        try {   
            if(CompanyAdmin::where('id',$id)->exists()){
                $data['company_admin'] = CompanyAdmin::where('id',$id)->get()->first();
                $data['company'] = Company::orderby('name','ASC')->get();
                return view('admin.company.add_admin')->with($data);
            } else{
                return back()->with('error','Company admin not exists please try again.');   
            }
        } catch (\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
        }
    }

}