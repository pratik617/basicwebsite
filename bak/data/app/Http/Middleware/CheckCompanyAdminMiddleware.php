<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;

class CheckCompanyAdminMiddleware
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
        } else if(Auth::user()){
            return redirect()->route('customer.dashboard');
        } else if(Auth::guard('company_admin')->check()){
            if(Request::segment(1) == "company" && Request::segment(2) == "" ){
                return $next($request);
            } else if(Request::segment(1) != "company"){
                return redirect("/");
            } else{
                return $next($request);
            }
        } else{
            if(Request::segment(1) != "company"){
                return redirect("/");
            } else{
                if(Auth::guard('company_admin')->check()){
                    return $next($request);
                } else{
                    if(Request::segment(1) == "company" && Request::segment(2) == "" ){
                        return $next($request);
                    } else{
                        return redirect("/");
                    }
                }
            }
        }
    }
}
