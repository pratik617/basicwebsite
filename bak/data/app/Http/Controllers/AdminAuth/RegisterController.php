<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Auth;
use App\Helper\RandomHelper;
use Mail;
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_no' => 'required|numeric|digits_between:1,15|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        dd("asdf");
        $otp = RandomHelper::randomOTP(6);
        session(['register_otp' => $otp]);
        Mail::send('mail.register.send_otp',['data'=>$data,'otp'=>$otp] , function($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject
                ('RideApp Verification Code');
            $message->from('rideapp@gmail.com','RideApp');
          });
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'contact_no' => $data['contact_no'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function set_contact_no(Request $request)
    {
        try {
            $rules = [
                'contact_no' => 'required|numeric|digits_between:1,15|unique:users',
                ];
            $customeMessage = [
                'contact_no.required' => 'contact number required.',
                'contact_no.numeric' => 'contact number must be digit.',
                'contact_no.digits_between' => 'contact number maximum 15 digit.',
                'contact_no.unique' => 'contact number is already register please try another.',
            ];
            $validator = Validator::make($request->all(),$rules,$customeMessage);
            if($validator->fails()){
                return back()->withErrors($validator);
            } else{
                $user_id = $request->id;
                $contact_no = strip_tags(trim($request->contact_no));
                $user = User::find($user_id);
                $user->contact_no = $contact_no;
                if($user->save()){
                    Auth::loginUsingId($user->id);
                    return redirect('/home');
                } else{
                    return back()->with('error','please try again.');
                }
            }
        } catch (Exception $e) {
            
        }
    }
}
