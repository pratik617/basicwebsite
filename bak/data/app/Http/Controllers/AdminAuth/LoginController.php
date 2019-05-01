<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Helper\Exceptions;
use Auth;
use App\CompanyAdmin;
use App\Helper\MailHelper;
use App\Helper\RandomHelper;
use Hash;
use App\User;
use App\Admin;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // return $this->middleware('admin:company_admin');
    }

    public function showLoginForm()
    {
        return view('admin.login.login');
    }
    
    public function login(request $request)                                                             
    {
        try{   
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
      
        if(Auth::guard('company_admin')->attempt(['email'=>$request->email,'password'=>$request->password,'status'=>'active'],$request->remember)){
            return redirect()->intended(route('company.dashboard'));
        }
        if(Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password],$request->remember)){
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->back()->withinput($request->only('email','remember'))->with('error','Please enter Valid Email ID or Password.');

       }catch(\Exception $e) {
            $Exceptions = New Exceptions;
            $Exceptions->sendException($e);
       }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('company_admin')->logout();
        return redirect('/');
    }

    public function companylogout()
    {
        Auth::guard('company_admin')->logout();
        return redirect('/');
    }

    public function forgot()
    {
        try {
            return view('admin.login.forgot');
        } catch (Exception $e) {
            Exceptions::exception($e);
        }
    }

    public function sendlink(Request $request)
    {
        try {
            $this->validate($request,[
                'email' => 'required|email',
            ]);
            $email = trim(strip_tags($request->email));
            if(Admin::where('email',$email)->exists()){
                $token = RandomHelper::forgotToken();
                $admin = Admin::where('email',$email)->get()->first();
                $adminUpdate = Admin::find($admin->id);
                $adminUpdate->password_reset = $token;
                $adminUpdate->save();
                $send = MailHelper::forgotAdmin($admin,$email,$token);
                if($send){
                    return back()->with('success','Rest Link Send Successfully Please Check Your Mail.');
                } else{
                    return back()->with('error','Email not send please try again.');
                }
            } else{
                return back()->with('error','Email address not exists in our system.');
            }
        } catch (Exception $e) {
            Exceptions::exception($e);   
        }
    }

    public function forgotAdmin($token)
    {
        try {
            $token = trim(strip_tags($token));
            if(Admin::where('password_reset',$token)->exists()){
                $data['token'] = $token;
            } else{
                $data['token'] = "";
            }
            return view('admin.forgot.forgot')->with($data);
        } catch (Exception $e) {
            
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $rules = [
                'password' => 'required|min:6',
                'cpassword' => 'required|same:password'
            ];
            $customeMessage = [
                'password.required' => 'Please enter password.',
                'password.min' => 'Please enter minimum 6 character.',
                'cpassword.required' => 'Please enter confirm password.',
                'password.min' => 'Please enter minimum 6 character.',
                'cpassword.same' => 'Password and confirm password not match.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if( $validator->fails() ){
                return back()->withInput()->withErrors($validator->errors());
            } else{
                $token = trim(strip_tags($request->token));
                $password = trim(strip_tags($request->password));
                $cpassword = trim(strip_tags($request->cpassword));
                if($password===$cpassword){
                    $admin = Admin::where('password_reset',$token)->get()->first();
                    if($admin){
                        $adminUpdate = Admin::find($admin->id);
                        $adminUpdate->password_reset = "";
                        $adminUpdate->password = Hash::make($password);
                        $adminUpdate->updated_at = date('Y-m-d H:i:s');
                        if($adminUpdate->save()){
                            return redirect('/admin');
                        }
                    }
                }
                return back();
            }
        } catch (Exception $e) {
            Exceptions::exception($e);   
        }
    }
}
