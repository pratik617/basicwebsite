<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use Auth;

class CheckUserMiddleware
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
        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.dashboard');
        } else if(Auth::guard('company_admin')->check()){
            return redirect()->route('company.dashboard');
        } else if(Auth::user()){
            if(Request::segment(1) == "admin"){
                return redirect("/");
            } else if(Request::segment(1) == "company"){
                return redirect("/");
            } else if(Request::segment(1) == "login" || Request::segment(1) == "register" ){
                return redirect()->route('customer.dashboard');
            } else{
                return $next($request);
            }
        } else{
            if(Request::segment(1) == "login" || Request::segment(1) == "register" ){
                return $next($request);   
            } else{
                return redirect("/");
            }
        }
    }
}
