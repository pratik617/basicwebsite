<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use App\User;
use Auth;

class SocialAuthFacebookController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            if($request->code!=""){
                $user = Socialite::driver('facebook')->user();
                $existUser = User::where('email',$user->email)->first();

                if(!$existUser) {
                  return redirect()->route('customer.register')->with(['s_name' => $user->name, 's_email' => $user->email, 's_id' => $user->id, 's_type' => 'facebook']);
                } else {
                  if(!$existUser->facebook_id) {
                    $existUser->facebook_id = $user->id;
                    $existUser->save();
                  }
                  Auth::loginUsingId($existUser->id);
                  return redirect()->route('customer.dashboard');
                }
            } else{
                return redirect()->to('/login');
            }
        }
        catch (Exception $e) {
            return 'error';
        }
    }
}
