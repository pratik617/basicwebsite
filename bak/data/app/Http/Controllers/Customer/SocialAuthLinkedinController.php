<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use App\User;
use Auth;

class SocialAuthLinkedinController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function callback(Request $request)
    {
        try {
            if($request->code!=""){
                $user = Socialite::driver('LinkedIn')->user();
                $existUser = User::where('email',$user->email)->first();

                if(!$existUser) {
                  return redirect()->route('customer.register')->with(['s_name' => $user->name, 's_email' => $user->email, 's_id' => $user->id, 's_type' => 'linkedin']);
                } else {
                  if(!$existUser->linkedin_id) {
                    $existUser->linkedin_id = $user->id;
                    $existUser->save();
                  }
                  Auth::loginUsingId($existUser->id);
                  return redirect()->route('customer.dashboard');
                }
            } else{
                return redirect()->to('/login');
            }
        } catch (Exception $e) {
            return 'error';
        }
    }
}
