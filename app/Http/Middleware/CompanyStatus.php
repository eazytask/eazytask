<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && !auth()->user()->company_roles->first()->company->sub_domain) {
            if ((auth()->user()->company_roles->first()->role != 1) && (auth()->user()->company_roles->first()->company->status == 0 || Carbon::parse(auth()->user()->company_roles->first()->company->expire_date)<Carbon::now()->toDateString())) {
                Auth::logout();
                $notification = array(
                    'message' => 'sorry! your company has temporarily blocked!',
                    'alert-type' => 'error'
                );
                return redirect()->route('login')->with($notification);
            }else{
                return $next($request);
            }
        }
        return redirect('/')->with('error', "You don't have admin access.");
    }
}
