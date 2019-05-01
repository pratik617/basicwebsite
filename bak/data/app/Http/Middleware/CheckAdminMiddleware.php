<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::guard('company_admin')->check()){
            return redirect()->route('company.dashboard');
        } else if(Auth::user()){
            return redirect()->route('customer.dashboard');
        } else if(Auth::guard('admin')->check()){
            if(Request::segment(1) == "admin" && Request::segment(2) == "" ){
                return redirect()->route('admin.dashboard');
            } else if(Request::segment(1) != "admin"){
                return redirect("/");
            } else{
                return $next($request);
            }
        } else{
            if(Request::segment(1) != "admin"){
                return redirect("/");
            } else{
                if(Auth::guard('admin')->check()){
                    return $next($request);
                } else{
                    if(Request::segment(1) == "admin" && Request::segment(2) == "" ){
                        return $next($request);
                    } else{
                        return redirect("/");
                    }
                }
            }
        }
    }
}
